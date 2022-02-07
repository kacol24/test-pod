<?php

namespace App\GraphQL\Mutations;

use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Hash;
use Joselfonseca\LighthouseGraphQLPassport\Events\PasswordUpdated;
use Joselfonseca\LighthouseGraphQLPassport\Exceptions\ValidationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Repositories\Facades\UserRepository;
use App\Models\User;
use App\Repositories\Facades\SubscriptionRepository;
use Ramsey\Uuid\Uuid;
use Illuminate\Auth\Events\Registered;
use Joselfonseca\LighthouseGraphQLPassport\GraphQL\Mutations\BaseAuthResolver;
use App\Notifications\FirstLogin;
/**
 * Class UpdateUser.
 */
class UserMutation extends BaseAuthResolver
{
    /**
     * @param $rootValue
     * @param array               $args
     * @param GraphQLContext|null $context
     * @param ResolveInfo         $resolveInfo
     *
     * @return array
     */
    
    public function subscribe($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {
        return SubscriptionRepository::subscribe($args['email']);
    }

    public function login($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {
        $jpcc = $this->loginJppc($args);
        if($jpcc) {
            $credentials = $this->buildCredentials($args);
            $response = $this->makeRequest($credentials);
            $user = $this->findUser($args['username']);

            $this->validateUser($user);
            if($user->first_login_email == 0) {
                $user->notify(new FirstLogin($user));
                $user->first_login_email = 1;
                $user->save();
            }
            $user->pct = 0;
            if($user->businesses->count()) {
                $user->pct = 50;
            }
            if($user->businesses->where('type','complete')->first()) {
                $user->pct = 100;
            }
            return array_merge(
                $response,
                [
                    'user' => $user,
                    'status' => 'success',
                    'message' => ''
                ],
            );
        }else {
            return array(
                'status' => 'error',
                'message' => 'Invalid username / password'
            );
        }
            
    }

    protected function findUser(string $username)
    {
        $model = $this->makeAuthModelInstance();

        if (method_exists($model, 'findForPassport')) {
            return $model->findForPassport($username);
        }

        return $model->where(config('lighthouse-graphql-passport.username'), $username)->first();
    }

    protected function validateUser($user)
    {
        $authModelClass = $this->getAuthModelClass();
        if ($user instanceof $authModelClass && $user->exists) {
            return;
        }

        throw (new ModelNotFoundException())->setModel(
            get_class($this->makeAuthModelInstance())
        );
    }

    public function loginJppc($args) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => env('JPCC_API_LOGIN'),
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => json_encode(array(
                'username' => $args['username'],
                'password' => $args['password'],
                'type' => 'Vendor'
          )),
          CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json",
          ),
        ));

        $resp = json_decode(curl_exec($curl),true);
        curl_close($curl);
        
        if($resp['api_status']) {
            $user = User::firstOrCreate(array(
                'jpcc_id' => $resp['data']['user']['id']
            ));
            $user->name = $resp['data']['user']['fullname'];
            $user->email = $resp['data']['user']['email'];
            $user->gender = $resp['data']['user']['gender'];
            $user->password = Hash::make($args['password']);
            $user->save();
            return $user;
        }else {
            return false;
        }
    }

    public function forgotPassword($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => env('JPCC_API_RESET').'?username='.$args['email'],
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => json_encode(array(
                'username' => $args['email']
          )),
          CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json",
            "Accept: application/json"
          ),
        ));
        
        $resp = json_decode(curl_exec($curl),true);
        curl_close($curl);
        if($resp['api_status']) {
            return array(
                'message' => $resp['message'],
                'status' => 'success'
            );
        }else {
            return array(
                'message' => $resp['message'],
                'status' => 'error'
            );
        }
    }
}
