<?php

namespace App\Providers;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('*', function ($view) {
            try {
                $view->with('siteSettings', SiteSetting::current());
            } catch (\Throwable $e) {
                $view->with('siteSettings', new SiteSetting());
            }
        });

        View::composer('frontend.pages.home.popup_notice', function ($view) {
            $view->with('popupNotice', \App\Models\Notice::where('show_in', 'p')->latest()->first());
        });
        View::composer('frontend.pages.home.hero_banner', function ($view) {
            $view->with('banners', \App\Models\Banner::where('status', 1)->get());
        });
        View::composer('frontend.pages.home.notice_ticker', function ($view) {
            $view->with('marqueeNotice', \App\Models\Notice::where('show_in', 'm')->latest()->first());
        });
        View::composer('frontend.pages.home.courses', function ($view) {
            $view->with('courses', \App\Models\Course::where('status', 1)->get());
        });
        View::composer('frontend.pages.home.counter', function ($view) {
            $view->with('counter', \App\Models\Counter::first());
        });
        View::composer('frontend.pages.home.official_messages', function ($view) {
            $view->with('messages', \App\Models\CollegeMessage::where('status', 1)->orderBy('order')->get());
        });
        View::composer('frontend.pages.home.events', function ($view) {
            $view->with('events', \App\Models\Event::where('status', 1)->get());
        });
        View::composer('frontend.pages.home.testimonials', function ($view) {
            $view->with('testimonials', \App\Models\Testimonial::all());
        });
    }
}
