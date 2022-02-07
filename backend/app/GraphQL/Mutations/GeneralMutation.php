<?php

namespace App\GraphQL\Mutations;

use App\Mail\Contact;
use App\Models\ContactForm;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Mail;
use Joselfonseca\LighthouseGraphQLPassport\GraphQL\Mutations\BaseAuthResolver;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Validator;

class GeneralMutation extends BaseAuthResolver
{
    public function submitContactForm($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {
        $validator = \Validator::make($args, [
            'email'   => 'required|email',
            'name'    => 'required',
            'message' => 'required',
        ]);

        if ($validator->fails()) {
            return [
                'status'  => 'error',
                'message' => $validator->errors()->first(),
            ];
        }

        ContactForm::create(array_merge(
            $validator->validated(),
            [
                'sent_to' => 'marketplace@jpcc.org',
            ]
        ));
        Mail::to('marketplace@jpcc.org')
            ->queue(new Contact($args));

        return [
            'status'  => 'success',
            'message' => 'Success submit contact form.',
        ];
    }
}
