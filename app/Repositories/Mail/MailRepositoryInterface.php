<?php

namespace App\Repositories\Mail;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

interface MailRepositoryInterface {
    /**
     * Send email
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public static function sendEmail(array $data);
    // public static function sendEmail(Request $request);

    public static function allTemplates();

    public static function sync();

    public static function template();
}