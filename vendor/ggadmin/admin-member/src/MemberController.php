<?php

namespace GG\Admin\Member;

use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

class MemberController
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header('Member');
            $content->description('Member list..');

            $content->body($this->grid());
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     *
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {
            $content->header('header');
            $content->description('description');

            $content->body($this->form()->edit($id));
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {
            $content->header('header');
            $content->description('description');

            $content->body($this->form());
        });
    }

    public function grid()
    {
        return Admin::grid(MemberModel::class, function (Grid $grid) {
            $grid->id('ID')->sortable();
            $grid->name()->display(function ($name) {
                return "<a tabindex=\"0\" class=\"btn btn-xs btn-twitter\" role=\"button\" data-toggle=\"popover\" data-html=true title=\"Usage\" data-content=\"<code>config('$name');</code>\">$name</a>";
            });
            //$grid->name();
			$grid->email();
			$grid->role();
			
            //$grid->description();

            $grid->created_at();
            $grid->updated_at();

            $grid->actions(function (Grid\Displayers\Actions $actions) {
                //$actions->disableEdit();
                $actions->disableDelete();
            
                $url = $actions->getResource().'/create?';
            
                $url .= http_build_query([
                    'title' => 'Re:'.$actions->row->title,
                    'to'    => $actions->row->from,
                ]);
            
                $actions->prepend("<a class=\"btn btn-xs\" href=\"$url\"><i class=\"fa fa-reply\"></i></a>");
                //$actions->prepend("<a href=\"http://www.google.com\">here</a>");
            });
            
            $grid->filter(function ($filter) {
                $filter->disableIdFilter();
                $filter->like('name');
                $filter->like('email');
            });
        });
    }

    public function form()
    {
        return Admin::form(MemberModel::class, function (Form $form) {
            $form->display('id', 'ID');
            $form->text('name')->rules('required');
			$form->text('email')->rules('required');
            //$form->textarea('value')->rules('required');
            $form->textarea('description');

            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
