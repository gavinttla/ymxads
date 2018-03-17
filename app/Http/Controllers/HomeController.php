<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Encore\Admin\Controllers\Dashboard;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //echo "here"; exit;
        //return view('home'); 
        
        return Admin::content(function (Content $content) {
        
            $content->header('Dashboard');
            $content->description('Description...');
        
            $content->row(Dashboard::title());
        
            $content->row(function (Row $row) {
        
                $row->column(4, function (Column $column) {
                    $column->append(Dashboard::environment());
                });
        
                    $row->column(4, function (Column $column) {
                        $column->append(Dashboard::extensions());
                    });
        
                        $row->column(4, function (Column $column) {
                            $column->append(Dashboard::dependencies());
                        });
            });
        });        
        
    }
}
