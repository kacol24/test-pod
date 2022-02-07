<?php

namespace App\GraphQL\Queries;

use App\Models\Business\Business;
use App\Models\Business\BusinessArea;
use App\Models\Business\Category;
use App\Models\ContactClick;
use App\Models\CouponClick;
use App\Models\User;
use App\Models\Visitor;
use App\Repositories\Facades\Instagram;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Str;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

/**
 * Class Brand.
 */
class BusinessQuery
{
    private function generateReport($model, $business, $startDate, $endDate, $groupBy = 'day')
    {
        $labels = [];
        $period = CarbonPeriod::create($startDate, '1 ' . $groupBy, $endDate)->toArray();

        $visitors = $model::where('business_id', $business->id)
                          ->whereBetween('date', [$startDate, $endDate])
                          ->get()
                          ->when($groupBy == 'month', function ($query) use (&$labels, $period) {
                              foreach ($period as $date) {
                                  $labels[$date->format('F Y')] = 0;
                              }

                              return $query->groupBy(function ($record){
                                  return Carbon::parse($record->date)->format('F Y');
                              });
                          })
                          ->when($groupBy == 'week', function ($query) use (&$labels, $period) {
                              foreach ($period as $date) {
                                  $labels[$date->format('M') . ' week ' . $date->weekOfMonth] = 0;
                              }

                              return $query->groupBy(function ($record) {
                                  $date = Carbon::parse($record->date);

                                  return $date->format('M').' week '.$date->weekOfMonth;
                              });
                          })
                          ->when(! $groupBy || $groupBy == 'day', function ($query) use (&$labels, $period) {
                              foreach ($period as $date) {
                                  $labels[$date->format('Y-m-d')] = 0;
                              }

                              return $query->groupBy('date');
                          })
                          ->map(function ($grouped) {
                              return $grouped->count();
                          })->toArray();
        $visitorData = array_merge($labels, $visitors);

        return [
            'data'          => array_values($visitorData),
            'labels'        => array_keys($visitorData),
            'sum'           => $sum = array_sum(array_values($visitorData)),
            'sum_formatted' => number_format($sum, 0, ',', '.'),
        ];
    }

    public function report($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo) {
        $slug = $args['slug'];
        $startDate = Carbon::parse($args['start_date']);
        $endDate = Carbon::parse($args['end_date']);
        $business = Business::slug($slug)->first();
        $groupBy = isset($args['group_by']) ? $args['group_by'] : 'day';

        $diff = $startDate->diffInDays($endDate);
        $beforeStartDate = $startDate->copy()->subDays($diff);
        $beforeEndDate = $endDate->copy()->subDays($diff);

        $data['visitors'] = $this->generateReport(
            Visitor::class,
            $business,
            $startDate,
            $endDate,
            $groupBy
        );
        $data['visitors']['last_period'] = $this->generateReport(
            Visitor::class,
            $business,
            $beforeStartDate,
            $beforeEndDate,
            $groupBy
        );

        $data['contacts'] = $this->generateReport(
            ContactClick::class,
            $business,
            $startDate,
            $endDate,
            $groupBy
        );
        $data['contacts']['last_period'] = $this->generateReport(
            ContactClick::class,
            $business,
            $beforeStartDate,
            $beforeEndDate,
            $groupBy
        );

        $data['coupons'] = $this->generateReport(
            CouponClick::class,
            $business,
            $startDate,
            $endDate,
            $groupBy
        );
        $data['coupons']['last_period'] = $this->generateReport(
            CouponClick::class,
            $business,
            $beforeStartDate,
            $beforeEndDate,
            $groupBy
        );

        return $data;
    }

    public function search($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {
        $q = $args['q'];

        $resultBusiness = Business::approved()->complete()
                                  ->where('name', 'like', "%{$q}%")
                                  ->get();
        foreach ($resultBusiness as $i => $business) {
            $resultBusiness[$i] = $this->businessTransformer($business);
        }

        $resultCategory = Category::active()
                                  ->where('parent_id', '!=', '0')
                                  ->where('title', 'like', "%{$q}%")
                                  ->has('businesses')
                                  ->orderBy('title', 'asc')
                                  ->get()
                                  ->map(function ($category) {
                                      $category->seo = $category->meta();

                                      return $category;
                                  });

        $resultTags = collect();
        Business::approved()->complete()
                ->whereNotNull('tags')
                ->pluck('tags')
                ->map(function ($tags) use (&$resultTags) {
                    $tags = explode(',', $tags);
                    foreach ($tags as $tag) {
                        $resultTags->push($tag);
                    }
                });
        $resultTags = $resultTags->unique()->filter(function ($tag) use ($q) {
            return Str::contains($tag, $q);
        });

        $data['business'] = $resultBusiness;
        $data['categories'] = $resultCategory;
        $data['tags'] = $resultTags;

        return $data;
    }

    public function __invoke($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {
        $data = [];

        $businesses = Business::approved()->complete()
                              ->when(isset($args['sort']), function ($query) use ($args) {
                                  if ($args['sort'] == 'random') {
                                      return $query->inRandomOrder();
                                  }

                                  $temp = explode('-', $args['sort']);
                                  $sort = $temp[0];
                                  $order = $temp[1];

                                  return $query->orderBy($sort, $order);
                              })
                              ->when(isset($args['category_id']) && $args['category_id'],
                                  function ($query) use ($args, &$data) {
                                      $data['filterCategory'] = Category::find($args['category_id']);

                                      return $query->join('business_categories', function ($join) use ($args) {
                                          $join->on('business.id', '=', 'business_categories.business_id')
                                               ->where('category_id', $args['category_id']);
                                      });
                                  })
                              ->when(isset($args['isTreasure']) && $args['isTreasure'], function ($query) use ($args) {
                                  return $query->where('is_treasure_arise', 1)
                                               ->where('treasure_arise_status', 'approved');
                              })
                              ->when(isset($args['areas']) && $args['areas'] != "", function ($query) use ($args) {
                                  return $query->join('business_areas', "business_areas.business_id", "=",
                                      "business.id")->where('area', $args['areas']);
                              })
                              ->when(isset($args['has_logo']) && $args['has_logo'], function ($query) use ($args) {
                                  return $query->whereNotNull('logo');
                              })
                              ->when(isset($args['has_coupons']) && $args['has_coupons'], function ($query) use ($args) {
                                      return $query->whereHas('coupons', function ($coupon){
                                          return $coupon->where('is_publish', true);
                                      });
                                  })
                              ->when(isset($args['has_images']), function ($query) use ($args) {
                                  return $query->has('images', '>=', $args['has_images']);
                              })
                              ->when(isset($args['is_wishlist']) && $args['is_wishlist'],
                                  function ($query) use ($args, $context) {
                                      return $query->whereHas('wishlists', function ($query) use ($context) {
                                          return $query->where('user_id', $context->user()->id);
                                      });
                                  })
                              ->paginate($args['limit'] ?? 15, ['*'], 'page', $args['page'] ?? 1);

        $userId = optional($context->user())->id;
        $user = $userId ? User::find($userId) : false;
        foreach ($businesses as $i => $business) {
            $businesses[$i]->is_loved = false;

            if ($user) {
                $wishlists = $user->wishlists->pluck('business_id')->toArray();
                $businesses[$i]->is_loved = in_array($business->id, $wishlists);
            }

            $businesses[$i]->logo_url = ($business->logo) ? image_url('logo', $business->logo) : null;
            $businesses[$i]->initial = get_initials($business->name);
            $businesses[$i]->firstcategory = [
                'id'    => $business->firstcategory()->id,
                'title' => $business->firstcategory()->title,
                'slug'  => $business->firstcategory()->slug,
            ];
            $businesses[$i]->secondcategory = [
                'id'    => $business->secondcategory()->id,
                'title' => $business->secondcategory()->title,
                'slug'  => $business->secondcategory()->slug,
            ];

            $images = [];
            foreach ($businesses[$i]->images as $image) {
                $images[] = [
                    'url'      => image_url('image', $image->image),
                    'filename' => $image->image,
                ];
            }
            $businesses[$i]->images = $images;
            $links = [];
            foreach ($business->links as $link) {
                $links[] = [
                    'url'   => $link->url,
                    'title' => $link->title,
                ];
            }
            $businesses[$i]->links = $links;
            $areas = [];
            foreach ($business->areas as $area) {
                $areas[] = $area->area;
            }
            $businesses[$i]->areas = $areas;
            $businesses[$i]->tags = ($business->tags) ? explode(",", $business->tags) : [];

            $instagram = [];
            if ($business->connectInstagram) {
                $medias = Instagram::getMedia($business->connectInstagram->ig_user_id,
                    $business->connectInstagram->access_token);
                $instagram = [
                    'id'       => $business->connectInstagram->ig_user_id,
                    'username' => $business->connectInstagram->username,
                    'medias'   => $medias,
                ];
            }
            $businesses[$i]->connectInstagram = $instagram;
        }
        $data['businesses'] = $businesses;

        $pages = [
            'count'        => $businesses->count(),
            'current_page' => $businesses->currentPage(),
            'total_data'   => $businesses->total(),
            'last_page'    => $businesses->lastPage(),
        ];
        $data['pagination'] = $pages;

        return $data;
    }

    public function businessAreas($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {
        return BusinessArea::all()->pluck('area')->unique();
    }

    /**
     * @param $rootValue
     * @param  array  $args
     * @param  GraphQLContext|null  $context
     * @param  ResolveInfo  $resolveInfo
     *
     * @return array
     */
    public function categories($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {
        $categories = Category::active()->orderBy('title', 'asc');
        if (isset($args['is_featured'])) {
            $categories = $categories->where('is_featured', 1);
        }
        $categories = $categories->get();
        foreach ($categories as $category) {
            $category->title = $category->title;
            $category->slug = $category->slug;
            $category->seo = $category->meta();
        }

        return category_tree($categories);
    }

    public function myBusiness($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {
        $businesses = Business::where('user_id', $context->user()->id)->get();
        foreach ($businesses as $i => $business) {
            $businesses[$i]->logo_url = ($business->logo) ? image_url('logo', $business->logo) : null;
            $businesses[$i]->initial = get_initials($business->name);
            $businesses[$i]->firstcategory = [
                'id'    => $business->firstcategory()->id,
                'title' => $business->firstcategory()->title,
                'slug'  => $business->firstcategory()->slug,
            ];
            $businesses[$i]->secondcategory = [
                'id'    => $business->secondcategory()->id,
                'title' => $business->secondcategory()->title,
                'slug'  => $business->secondcategory()->slug,
            ];

            $images = [];
            foreach ($businesses[$i]->images as $image) {
                $images[] = [
                    'url'      => image_url('image', $image->image),
                    'filename' => $image->image,
                ];
            }
            $businesses[$i]->images = $images;
            $links = [];
            foreach ($business->links as $link) {
                $links[] = [
                    'url'   => $link->url,
                    'title' => $link->title,
                ];
            }
            $businesses[$i]->links = $links;
            $areas = [];
            foreach ($business->areas as $area) {
                $areas[] = $area->area;
            }
            $businesses[$i]->areas = $areas;
            $businesses[$i]->tags = ($business->tags) ? explode(",", $business->tags) : [];

            $instagram = [];
            if ($business->connectInstagram) {
                $medias = Instagram::getMedia($business->connectInstagram->ig_user_id,
                    $business->connectInstagram->access_token);
                $instagram = [
                    'id'       => $business->connectInstagram->ig_user_id,
                    'username' => $business->connectInstagram->username,
                    'medias'   => $medias,
                ];
            }
            $businesses[$i]->connectInstagram = $instagram;
        }
        $data['businesses'] = $businesses;

        return $data;
    }

    public function findBySlug($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {
        $business = Business::where('slug', $args['slug'])->first();

        if (!$business) {
            return null;
        }

        $user = null;
        if($context->user()) {
            $userId = $context->user()->id;
            $user = User::find($userId);
        }

        $business = $this->businessTransformer($business, $user);

        $relateds = Business::where('id', '!=', $business->id)
                            ->whereHas('categories',
                                function ($query) use ($business) {
                                    return $query->whereIn('category_id', $business->categories->pluck('category_id'));
                                })
                            ->when(isset($args['related_limit']), function ($query) use ($args) {
                                return $query->limit($args['related_limit']);
                            })
                            ->inRandomOrder()
                            ->get();

        foreach ($relateds as $i => $related) {
            $relateds[$i] = $this->businessTransformer($related);
        }

        $business->related = $relateds;

        if(isset($args['is_visit'])) {
            $bearerToken = $context->request->bearerToken();
            $parsedJwt = (new \Lcobucci\JWT\Parser())->parse($bearerToken);
            if ($parsedJwt->hasHeader('jti')) {
              $tokenId = $parsedJwt->getHeader('jti');
            } elseif ($parsedJwt->hasClaim('jti')) {
              $tokenId = $parsedJwt->getClaim('jti');
            }

            Visitor::firstOrCreate(array(
              'business_id' => $business->id,
              'token' => $tokenId,
              'date' => date("Y-m-d")
            ));
        }

        return $business;
    }

    private function businessTransformer($business, $user = null)
    {
        $business->is_loved = false;
        if ($user) {
            $wishlists = $user->wishlists->pluck('business_id')->toArray();
            $business->is_loved = in_array($business->id, $wishlists);
        }
        $business->logo_url = ($business->logo) ? image_url('logo', $business->logo) : null;
        $business->tags = ($business->tags) ? explode(",", $business->tags) : [];
        $business->initial = get_initials($business->name);
        $business->firstcategory = [
            'id'    => $business->firstcategory()->id,
            'title' => $business->firstcategory()->title,
            'slug'  => $business->firstcategory()->slug,
        ];
        $business->secondcategory = [
            'id'    => $business->secondcategory()->id,
            'title' => $business->secondcategory()->title,
            'slug'  => $business->secondcategory()->slug,
        ];
        $images = [];
        foreach ($business->images as $image) {
            $images[] = [
                'url'      => image_url('image', $image->image),
                'filename' => $image->image,
            ];
        }
        $business->images = $images;

        $links = [];
        foreach ($business->links as $link) {
            $links[] = [
                'url'   => $link->url,
                'title' => $link->title,
            ];
        }
        $business->links = $links;
        $areas = [];
        foreach ($business->areas as $area) {
            $areas[] = $area->area;
        }
        $business->areas = $areas;

        $instagram = [];
        if ($business->connectInstagram) {
            $medias = Instagram::getMedia($business->connectInstagram->ig_user_id,
                $business->connectInstagram->access_token);
            $instagram = [
                'id'       => $business->connectInstagram->ig_user_id,
                'username' => $business->connectInstagram->username,
                'medias'   => $medias,
            ];
        }
        $business->connectInstagram = $instagram;

        return $business;
    }
}
