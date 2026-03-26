<?php

namespace App\Http\Controllers;

use App\Emails\Jobs\SendBatchEmailsJob;
use App\Http\Requests\Web\Register__CRUD_STUDLY_SINGULAR__Request;
use App\Models\__CRUD_STUDLY_SINGULAR__;
use App\Models\MailTemplate;
use Laraeast\LaravelSettings\Facades\Settings;

class Register__CRUD_STUDLY_SINGULAR__Controller extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Register__CRUD_STUDLY_SINGULAR__Request $request)
    {
        $__CRUD_CAMEL_SINGULAR__ = __CRUD_STUDLY_SINGULAR__::query()->forceCreate($request->validated());

        if ($templateId = Settings::get('__CRUD_SNAKE_PLURAL___welcome_email_template')) {
            $template = MailTemplate::find($templateId);

            if ($template) {
                $emailsPerDay = Settings::get('emails_per_day', 100);

                SendBatchEmailsJob::dispatch(
                    [$__CRUD_CAMEL_SINGULAR__],
                    $template->subject,
                    $template->content,
                    $emailsPerDay,
                );
            }
        }

        flash()->success(__('__CRUD_KEBAB_PLURAL__.messages.registered'));

        return back();
    }
}
