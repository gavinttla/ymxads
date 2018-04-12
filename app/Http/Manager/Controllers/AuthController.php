<?php

namespace App\Http\Manager\Controllers;

use App\Http\Manager\Auth\Database\Administrator;
use App\Http\Manager\Facades\Manager;
use App\Http\Manager\Form;
use App\Http\Manager\Layout\Content;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

use Illuminate\Foundation\Auth\AuthenticatesUsers;


/**
 * 
 * Route: check Manager::registerAuthRoutes() that register some routes.
 * 
 * 
 * @author gbcui
 *
 */
class AuthController extends Controller
{
    
    use AuthenticatesUsers;
    
    /**
     * Login page.
     *
     * @return \Illuminate\Contracts\View\Factory|Redirect|\Illuminate\View\View
     */
    public function getLogin()
    {
        dd("App\Http\Manager\Controllers\AuthController::getLogin");
        if (!Auth::guard('web')->guest()) {
            return redirect(config('manager.route.prefix'));
        }

        return view('manager::login');
    }

    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function postLogin(Request $request)
    {
        dd("App\Http\Manager\Controllers\AuthController::postLogin");
        $credentials = $request->only(['username', 'password']);

        $validator = Validator::make($credentials, [
            'username' => 'required', 'password' => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput()->withErrors($validator);
        }

        if (Auth::guard('admin')->attempt($credentials)) {
            admin_toastr(trans('admin.login_successful'));

            return redirect()->intended(config('manager.route.prefix'));
        }

        return Redirect::back()->withInput()->withErrors(['username' => $this->getFailedLoginMessage()]);
    }

    /**
     * User logout.
     *
     * @return Redirect
     */
    public function getLogout()
    {

        //$this->guard('web')->logout();
        Auth::guard('web')->logout();
        
        session()->forget('url.intented');
        
        return redirect('/');
        
        
        /*
        Auth::guard('admin')->logout();

        session()->forget('url.intented');

        return redirect(config('admin.route.prefix'));
        */
    }

    /**
     * User setting page.
     *
     * @return mixed
     */
    public function getSetting()
    {
        return Manager::content(function (Content $content) {
            $content->header(trans('admin.user_setting'));
            $form = $this->settingForm();
            $form->tools(
                function (Form\Tools $tools) {
                    $tools->disableBackButton();
                    $tools->disableListButton();
                }
            );
            $content->body($form->edit(Manager::user()->id));
        });
    }

    /**
     * Update user setting.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function putSetting()
    {
        return $this->settingForm()->update(Manager::user()->id);
    }

    /**
     * Model-form for user setting.
     *
     * @return Form
     */
    protected function settingForm()
    {
        return Administrator::form(function (Form $form) {
            $form->display('username', trans('admin.username'));
            $form->text('name', trans('admin.name'))->rules('required');
            $form->image('avatar', trans('admin.avatar'));
            $form->password('password', trans('admin.password'))->rules('confirmed|required');
            $form->password('password_confirmation', trans('admin.password_confirmation'))->rules('required')
                ->default(function ($form) {
                    return $form->model()->password;
                });

            $form->setAction(admin_base_path('auth/setting'));

            $form->ignore(['password_confirmation']);

            $form->saving(function (Form $form) {
                if ($form->password && $form->model()->password != $form->password) {
                    $form->password = bcrypt($form->password);
                }
            });

            $form->saved(function () {
                admin_toastr(trans('admin.update_succeeded'));

                return redirect(admin_base_path('auth/setting'));
            });
        });
    }

    /**
     * @return string|\Symfony\Component\Translation\TranslatorInterface
     */
    protected function getFailedLoginMessage()
    {
        return Lang::has('auth.failed')
            ? trans('auth.failed')
            : 'These credentials do not match our records.';
    }
}
