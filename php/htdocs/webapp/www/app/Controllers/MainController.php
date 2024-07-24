<?php
namespace App\Controllers;

use App\Repositories\Language\ILanguageRepository;
use App\Repositories\Member\IMemberRepository;
use Helpers\Csrf;
use Helpers\Gump;
use Helpers\ReCaptcha;
use View;
use App\Core\Controller;
use Helpers\Session;
use Helpers\Url;
use Config\Config;
use AMQPMailer;

/**
 * Sample controller showing a construct and 2 methods and their typical usage.
 */
class MainController extends Controller
{
    protected $memberRepo = null;
    protected $languageRepo = null;

    /**
     * Call the parent construct
     */
    public function __construct(
        IMemberRepository $memberRepo,
        ILanguageRepository $languageRepo
    )
    {
        parent::__construct();

        $this->memberRepo = $memberRepo;
        $this->languageRepo = $languageRepo;
    }

    public function before()
    {
        return parent::before();
    }

    /**
     * Define Index page title and load template files
     */
    public function index()
    {
        if(Config::get("app.isMaintenance")
            && !in_array($_SERVER['REMOTE_ADDR'], Config::get("app.ips")))
        {
            Url::redirect("maintenance");
        }

        if(Session::get('memberID'))
        {
            $member = $this->memberRepo->get(Session::get('memberID'));

            if (!$member) Url::redirect("members/login");

            if(!$member->profile->complete) Url::redirect("members/profile");

            Url::redirect("events");
        }

        $data['menu'] = 5;
        $data["isMain"] = true;

        return View::make('Main/Index')
            ->shares("title", __("welcome_text"))
            ->shares("data", $data);
    }

    public function maintenance()
    {
        if(!Config::get("app.isMaintenance"))
        {
            Url::redirect("/");
        }

        return View::make('Main/Maintenance')
            ->shares("title", __("maintenance_work"));
    }

    public function checkMaintenance() {
        $response = ["isMaintenance" => Config::get("app.isMaintenance")];
        echo json_encode($response);
    }

    /**
     * About Us Page
     */
    public function about()
    {
        $data['title'] = __('about_title');
        $data['menu'] = 6;

        return View::make('Main/About')
            ->shares("title", __("about_title"))
            ->shares("data", $data);
    }

    /**
     * Contact Us page
     */
    public function contactUs()
    {
        $data['menu'] = 5;
        $data['title'] = __('contact_us_title');
        $data['csrfToken'] = Csrf::makeToken();

        $error = [];

        $languages = $this->languageRepo->all();
        $member = $this->memberRepo->get(Session::get('memberID'));

        if (isset($_POST['email'])) {
            $gump = new Gump();
            $gump->validation_rules([
                'name' => 'required',
                'email' => 'required|valid_email',
                'message' => 'required|max_len,2000',
                'lang' => 'required'
            ]);

            $gump->filter_rules([
                'name' => 'trim|sanitize_string',
                'email' => 'trim|sanitize_email',
                'message' => 'trim|sanitize_string',
                'lang' => 'trim|sanitize_string'
            ]);

            $valid_data = $gump->run($_POST);

            $adminEmail = Config::get("app.email");

            if($valid_data) {
                if(Config::get("app.type") == "remote") {
                    if (ReCaptcha::check()) {
                        AMQPMailer::sendView(
                            "Emails/Common/NotifyContactUs",
                            [
                                "name" => $valid_data["name"],
                                "email" => $valid_data["email"],
                                "language" => $valid_data["lang"],
                                "userMessage" => $valid_data["message"]
                            ],
                            [$adminEmail],
                            "Contact Form Notification"
                        );

                        $_POST = [];
                        $data["success"] = __("contact_us_successful");
                    } else {
                        $error[] = __('captcha_wrong');
                    }
                }
            } else {
                $error[] = __("required_fields_empty_error");
            }
        }

        return View::make('Main/ContactUs')
            ->shares("title", __("contact_us_title"))
            ->shares("member", $member)
            ->shares("languages", $languages)
            ->shares("error", $error)
            ->shares("data", $data);
    }
}
