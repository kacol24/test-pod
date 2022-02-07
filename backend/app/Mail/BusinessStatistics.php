<?php

namespace App\Mail;

use App\Models\ContactClick;
use App\Models\CouponClick;
use App\Models\Visitor;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BusinessStatistics extends Mailable
{
    use Queueable, SerializesModels;

    public $business;

    protected $startDate;

    protected $endDate;

    public function __construct($business, $startDate, $endDate)
    {
        $this->business = $business;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $business = $this->business;

        $startDate = $this->startDate;
        $endDate = $this->endDate;

        $visitors = Visitor::whereBetween('date', [$startDate, $endDate])->get();
        $businessVisitors = $visitors->groupBy('business_id')->count();
        $businessVisitors = $businessVisitors == 0 ? 1 : $businessVisitors;

        $contacts = ContactClick::whereBetween('date', [$startDate, $endDate])->get();
        $businessContacts = $contacts->groupBy('business_id')->count();
        $businessContacts = $businessContacts == 0 ? 1 : $businessContacts;

        $coupons = CouponClick::whereBetween('date', [$startDate, $endDate])->get();
        $businessCoupons = $coupons->groupBy('business_id')->count();
        $businessCoupons = $businessCoupons == 0 ? 1 : $businessCoupons;

        $data = [
            'visitor_sum' => $business->visitors->whereBetween('date', [$startDate, $endDate])->count(),
            'contact_sum' => $business->contactClicks->whereBetween('date', [$startDate, $endDate])->count(),
            'coupon_sum'  => $business->couponClicks->whereBetween('date', [$startDate, $endDate])->count(),
            'avg'         => [
                'visitor_sum' => $visitors->count() / $businessVisitors,
                'contact_sum' => $contacts->count() / $businessContacts,
                'coupon_sum'  => $coupons->count() / $businessCoupons,
            ],
        ];

        return $this->subject($business->name.', ini performa bulanan halaman bisnismu')
                    ->markdown('emails.business.statistics', $data);
    }
}
