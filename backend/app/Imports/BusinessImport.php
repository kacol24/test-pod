<?php

namespace App\Imports;

use App\Models\Business\Business;
use App\Models\Business\BusinessCategory;
use App\Models\Business\BusinessImage;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Str, Image;

class BusinessImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if(isset($row["user_id_myjpcc"]) && $row["user_id_myjpcc"]) {
            $user = User::firstOrCreate(array(
                'jpcc_id' => $row["user_id_myjpcc"]
            ));
            $user->name = ($row['name'] =='no need') ? "" : $row['name'];
            $user->email = ($row['email']=='no need') ? "email".$row["user_id_myjpcc"]."@email.com" : $row['email'];
            $user->save();

            $logo = "";

            if($row['business_logo']) {
                $url = "https://selaras.io/jpcc/".$row['business_logo'];
                $ext = pathinfo($url, PATHINFO_EXTENSION);
                $logo =  sha1(Str::random(32)).".".$ext;

                $path = storage_path('app/public/logo');
                if(!file_exists($path)){
                  mkdir($path, 0755, true);
                }
                //$logotarget = $path.'/'.$logo;
                //Image::make($url)->fit(500,500)->save($logotarget);
            }

            $company_size = "<10 orang";
            if($row['number_of_employee']>10 && $row['number_of_employee']<50){
                $company_size = "10-49 orang";
            }else if($row['number_of_employee']>50 && $row['number_of_employee']<250){
                $company_size = "50-249 orang";
            }else if($row['number_of_employee']>250){
                $company_size = ">250 orang";
            }
            $business = Business::create(array(
                'user_id' => $user->id,
                'slug' => slugify($row['listing_title']),
                'name' => $row['listing_title'],
                'email' => $row['business_email'],
                'address' => $row['address'],
                'description' => $row['description'],
                'logo' => $logo,
                'location' => $row['location'],
                'phone' => $row['phone'],
                'whatsapp' => $row['whatsapp'],
                'website' => $row['website'],
                'instagram' => $row['instagram'],
                'youtube' => $row['youtube'],
                'establish_since' => $row['established_since'],
                'company_type' => ($row['legal_type'] == 'N/A') ? "Belum Berbadan Usaha" : $row['legal_type'],
                'company_size' => $company_size,
                'ownership' => "Pribadi",
                'status' => 'approved',
                'type' => 'complete'
            ));

            BusinessCategory::create(array(
                'business_id' => $business->id,
                'category_id' => $row['first_category']
            ));

            BusinessCategory::create(array(
                'business_id' => $business->id,
                'category_id' => $row['second_category']
            ));

            for($i=1;$i<=8;$i++) {
                if($row['pic_'.$i] && $row['pic_'.$i]!='undefined') {
                    $url = "https://selaras.io/jpcc/".$row['pic_'.$i];
                    $ext = pathinfo($url, PATHINFO_EXTENSION);
                    $image =  sha1(Str::random(32)).".".$ext;

                    $path = storage_path('app/public/image');
                    if(!file_exists($path)){
                      mkdir($path, 0755, true);
                    }
                    //$imagetarget = $path.'/'.$image;
                    //Image::make($url)->fit(500,500)->save($imagetarget);

                    BusinessImage::create(array(
                        'business_id' => $business->id,
                        'image' => $image
                    ));
                }
            }

            return $business;
        }else {
            echo $row['listing_title']."\n";
        }
    }
}
