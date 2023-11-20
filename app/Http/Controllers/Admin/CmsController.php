<?php

namespace App\Http\Controllers\Admin;

use App\EmailSetting;
use App\Http\Controllers\Controller;
use App\Models\CmsComponent;
use App\Models\CmsPages;
use App\Models\CmsImage;
use Illuminate\Http\Request;
use App\Models\Banner;
use Validator;

class CmsController extends Controller
{
    public function firstSectionIndex()
    {
        try {
            if (request()->ajax()) {
                return datatables()->of(CmsPages::where('section_name', 'first')->get())
                    ->addColumn('action', function ($data) {
                        return '<a title="Edit Section" href="first-section-edit-form/' . $data->id . '" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>&nbsp;<a title="Edit Component" href="first-section-component-edit/' . $data->id . '" class="btn btn-success btn-sm"><i class="fas fa-edit"></i></a>&nbsp;<button title="Delete" style="display:none" type="button" name="delete" id="' . $data->id . '" class="delete btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>';
                    })->rawColumns(['action'])->make(true);
            }
        } catch (\Exception $ex) {
            return redirect('/')->with('error', $ex->getMessage());
        }
        return view('admin.cms.first-section-index');
    }

    public function cmsFirstSectionEditForm($id)
    {
        $cmsSection = CmsPages::where('id', $id)->with('components')->first();

        return view('admin.cms.first-section-edit-form', compact('cmsSection'));
    }

    public function cmsFirstSectionComponent()
    {
        $cmsSection = CmsPages::where('section_name', 'first')->first();
        return view('admin.cms.first-section-component-form', compact('cmsSection'));
    }

    public function firstSectionComponentEdit($id)
    {
        $cmsSection = CmsComponent::where('cms_page_id', $id)->get();
        return view('admin.cms.first-section-component-edit', compact('cmsSection', 'id'));
    }

    public function firstSectionComponentUpdate($id, Request $request)
    {
        try {
            foreach ($request->cms_components_id as $index => $cms_components_id) {
                CmsComponent::where('id', $cms_components_id)->update([
                    'cms_page_id' => $id,
                    'title' => $request->title[$index],
                    'description' => $request->description[$index],
                ]);
            }
            return redirect('admin/cms-first-section')->with('success', 'Child Component Updated Successfully!');
        } catch (\Exception $ex) {
            return redirect('admin/cms-first-section')->with('error', $ex->getMessage());
        }
    }

    public function firstSectionComponentStore(Request $request)
    {
        try {
            foreach ($request->title as $index => $title) {
                CmsComponent::create([
                    'cms_page_id' => $request->cms_page_id,
                    'title' => $title,
                    'description' => $request->description[$index],
                ]);
            }
            return redirect('admin/cms-first-section')->with('success', 'Child Component Added Successfully!');
        } catch (\Exception $ex) {
            return redirect('admin/cms-first-section')->with('error', $ex->getMessage());
        }
    }

    public function firstSectionUpdate($id, Request $request)
    {
        try {
            CmsPages::where('id', $id)->update([
                'page_name' => 'Home',
                'heading' => $request->heading
            ]);

            if ($id == 2) {
                foreach ($request->title as $index => $title) {
                    CmsComponent::where('id', $request->component_id[$index])->update([
                        'title' => $title,
                    ]);
                }
            }

            if ($id == 1) {
                return redirect('admin/cms-first-section')->with('success', 'Section Updated Successfully!');
            } elseif ($id == 2) {
                return redirect('admin/cms-second-section')->with('success', 'Section Updated Successfully!');
            } else {
                return redirect('admin/cms-third-section')->with('success', 'Section Updated Successfully!');
            }
        } catch (\Exception $ex) {
            return redirect('admin/cms-first-section')->with('error', $ex->getMessage());
        }
    }

    public function secondSectionIndex()
    {
        try {
            if (request()->ajax()) {
                return datatables()->of(CmsPages::where('section_name', 'second')->get())
                    ->addColumn('action', function ($data) {
                        return '<a title="Edit Section" href="first-section-edit-form/' . $data->id . '" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>&nbsp;<a title="Edit Component" href="second-section-component-edit/' . $data->id . '" class="btn btn-success btn-sm"><i class="fas fa-edit"></i></a>&nbsp;<button title="Delete" type="button" style="display:none" name="delete" id="' . $data->id . '" class="delete btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>';
                    })->rawColumns(['action'])->make(true);
            }
            // dd('dasds');
        } catch (\Exception $ex) {
            return redirect('/')->with('error', $ex->getMessage());
        }
        return view('admin.cms.second-section-index');
    }

    public function cmsSecondSectionComponent()
    {
        $cmsSection = CmsPages::where('section_name', 'second')->first();
        return view('admin.cms.second-section-component-form', compact('cmsSection'));
    }

    public function secondSectionComponentStore(Request $request)
    {
        //dd($request->all());
        if ($request->file('image')) {
            $files = $request->file('image');
            foreach ($files as $file) {
                $image_type = $file->getClientOriginalExtension();
                $new_image_name = date('YmdHis') . '-front' . rand(100, 1000) . '-' . $file->getClientOriginalName();
                $file->move(public_path() . '/uploads/cms/slider/', $new_image_name);
                CmsImage::create([
                    'cms_page_id' => $request->cms_pages_id,
                    // 'cms_component_id' => $request->cms_page_id,
                    'image_type' => $image_type,
                    'image' => $new_image_name,
                ]);
            }
        }

        return redirect('admin/cms-second-section')->with('success', 'Slider Images Added Successfully!');
    }

    public function secondSectionComponentEdit($id)
    {
        $cmsSection = CmsImage::where('cms_page_id', $id)->get();
        return view('admin.cms.second-section-component-edit', compact('cmsSection', 'id'));
    }

    public function secondSectionComponentUpdate($id, Request $request)
    {
        // dd($request->all());
        $deletedRows = CmsImage::where('cms_page_id', $id)->delete();
        $cms_images_ids = $request->cms_images_id;
        foreach ($cms_images_ids as $index => $cms_images_id) {
            if (isset($request->file('image')[$index])) {
                $file = $request->file('image')[$index];
                $image_type = $file->getClientOriginalExtension();
                $new_image_name = date('YmdHis') . '-front' . rand(100, 1000) . '-' . $file->getClientOriginalName();
                $file->move(public_path() . '/uploads/cms/slider/', $new_image_name);
            } else {
                $new_image_name = $request->image_val[$index];
                $image_type = "jpg";
            }
            CmsImage::create([
                'cms_page_id' => $id,
                // 'cms_component_id' => $request->cms_page_id,
                'image_type' => $image_type,
                'image' => $new_image_name,
            ]);
        }

        return redirect('admin/cms-second-section')->with('success', 'Slider Images Added Successfully!');
    }

    public function thirdSectionIndex()
    {
        try {
            if (request()->ajax()) {
                return datatables()->of(CmsPages::where('section_name', 'third')->get())
                    ->addColumn('action', function ($data) {
                        return '<a title="Edit Section" href="first-section-edit-form/' . $data->id . '" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>&nbsp;<a title="Edit Component" href="third-section-component-edit/' . $data->id . '" class="btn btn-success btn-sm"><i class="fas fa-edit"></i></a>';
                    })->rawColumns(['action'])->make(true);
            }
            // dd('dasds');
        } catch (\Exception $ex) {
            return redirect('/')->with('error', $ex->getMessage());
        }
        return view('admin.cms.third-section-index');
    }

    public function cmsThirdSectionComponent()
    {
        $cmsSection = CmsPages::where('section_name', 'third')->first();
        return view('admin.cms.third-section-component-form', compact('cmsSection'));
    }

    public function thirdSectionComponentStore(Request $request)
    {
        //dd($request->all());
        if ($request->file('image')) {
            $files = $request->file('image');
            foreach ($files as $file) {
                $image_type = $file->getClientOriginalExtension();
                $new_image_name = date('YmdHis') . '-third' . rand(100, 1000) . '-' . $file->getClientOriginalName();
                $file->move(public_path() . '/uploads/cms/slider/', $new_image_name);
                CmsImage::create([
                    'cms_page_id' => $request->cms_page_id,
                    // 'cms_component_id' => $request->cms_page_id,
                    'image_type' => $image_type,
                    'image' => $new_image_name,
                ]);
            }

            CmsComponent::create([
                'cms_page_id' => $request->cms_page_id,
                // 'title' => $title,
                'description' => $request->description,
            ]);
        }

        return redirect('admin/cms-third-section')->with('success', 'Data Added Successfully!');
    }

    public function thirdSectionComponentEdit($id)
    {
        $description = CmsComponent::where('cms_page_id', $id)->first();
        $cmsSection = CmsImage::where('cms_page_id', $id)->get();
        return view('admin.cms.third-section-component-edit', compact('cmsSection', 'id', 'description'));
    }

    public function thirdSectionComponentUpdate($id, Request $request)
    {
        //dd($request->all());
        try {
            $deletedRows = CmsImage::where('cms_page_id', $id)->delete();
            $cms_images_ids = $request->cms_images_id;

            CmsComponent::where('id', $request->cms_components_id)->update([
                'cms_page_id' => $id,
                // 'title' => $request->title[$index],
                'description' => $request->description,
            ]);
            foreach ($cms_images_ids as $index => $cms_images_id) {
                if (isset($request->file('image')[$index])) {
                    $file = $request->file('image')[$index];
                    $image_type = $file->getClientOriginalExtension();
                    $new_image_name = date('YmdHis') . '-front' . rand(100, 1000) . '-' . $file->getClientOriginalName();
                    $file->move(public_path() . '/uploads/cms/slider/', $new_image_name);
                } else {
                    $new_image_name = $request->image_val[$index];
                    $image_type = "jpg";
                }
                CmsImage::create([
                    'cms_page_id' => $id,
                    // 'cms_component_id' => $request->cms_page_id,
                    'image_type' => $image_type,
                    'image' => $new_image_name,
                ]);
            }

            return redirect('admin/cms-third-section')->with('success', 'Slider Images Added Successfully!');
        } catch (\Exception $ex) {
            return redirect('admin/cms-third-section')->with('error', $ex->getMessage());
        }
    }

    public function TermsAndConditionsSectionIndex()
    {
        try {
            if (request()->ajax()) {
                return datatables()->of(CmsPages::where('section_name', 'termsandconditions')->get())
                    ->addColumn('action', function ($data) {
                        return '<a title="Edit Component" href="terms-and-conditions-edit/' . $data->id . '" class="btn btn-success btn-sm"><i class="fas fa-edit"></i></a>';
                    })->rawColumns(['action'])->make(true);
            }
        } catch (\Exception $ex) {
            return redirect('/')->with('error', $ex->getMessage());
        }
        return view('admin.cms.terms-and-condition-index');
    }

    public function createtermsandconditionform()
    {
        $cmsSection = CmsPages::where('section_name', 'termsandconditions')->first();
        return view('admin.cms.create-terms-and-condition-form', compact('cmsSection'));
    }

    public function termsandconditionsstore(Request $request)
    {
        // dd($request->all());
        try {
            CmsComponent::create([
                'cms_page_id' => $request->cms_page_id,
                // 'title' => $title,
                'description' => $request->description,
            ]);
            return redirect('admin/cms-third-section')->with('success', 'Data Added Successfully!');
        } catch (\Exception $ex) {
            return redirect('admin/cms-third-section')->with('error', $ex->getMessage());
        }
    }

    public function termsandconditionsedit($id)
    {
        $CmsComponent = CmsComponent::where('cms_page_id', $id)->first();
        return view('admin.cms.terms-and-conditions-edit', compact('id', 'CmsComponent'));
    }

    public function termsandconditionsupdate($id, Request $request)
    {
        //dd($request->all());
        try {
            CmsComponent::where('id', $request->CmsComponent_id)->update([
                'cms_page_id' => $id,
                'description' => $request->description,
            ]);
            return redirect('admin/cms-terms-and-conditions-section')->with('success', 'Updated Successfully!');
        } catch (\Exception $ex) {
            return redirect('admin/cms-terms-and-conditions-section')->with('error', $ex->getMessage());
        }
    }

    public function homeBannerSection()
    {
        $banner = Banner::where('name', 'home banner')->first();

        return view('admin.cms.home-banner', compact('banner'));
    }

    public function homeBannerStore(Request $request)
    {

        if ($request->file('image')) {
            $file = $request->file('image');
            $image_type = $file->getClientOriginalExtension();
            $new_image_name = date('YmdHis') . 'banner' . rand(100, 1000) . '.' . $file->getClientOriginalExtension();
            $path = '/front/images/';
            $file->move(public_path() . $path, $new_image_name);
            $new_image_name_db = $path . '' . $new_image_name;
        }

        $check = Banner::where('name', 'home banner')->count();
        if ($check > 0) {
            Banner::where('id', $request->id)->update([
                'banner_img' => $new_image_name_db,
                'name' => 'home banner',
                'status' => 1,
            ]);
        } else {
            Banner::create([
                'banner_img' => $new_image_name_db,
                'name' => 'home banner',
                'status' => 1,
            ]);
        }

        return redirect('admin/home-banner-section')->with('success', 'Banner Added Successfully!');
    }

    public function searchBannerSection()
    {
        $banner = Banner::where('name', 'search banner')->first();
        return view('admin.cms.search-banner', compact('banner'));
    }

    public function searchBannerStore(Request $request)
    {
        // dd($request->all());
        if ($request->file('image')) {
            $file = $request->file('image');
            $image_type = $file->getClientOriginalExtension();
            $new_image_name = date('YmdHis') . 'searchbanner' . rand(100, 1000) . '.' . $file->getClientOriginalExtension();
            $path = '/front/images/';
            $file->move(public_path() . $path, $new_image_name);
            $new_image_name_db = $path . '' . $new_image_name;
        }
        //dd($new_image_name_db);
        $check = Banner::where('name', 'search banner')->count();
        if ($check > 0) {
            //dd($check);
            Banner::where('id', $request->id)->update([
                'banner_img' => $new_image_name_db,
                'name' => 'search banner',
                'status' => 1,
            ]);
        } else {
            Banner::create([
                'banner_img' => $new_image_name_db,
                'name' => 'search banner',
                'status' => 1,
            ]);
        }

        return redirect('admin/search-banner-section')->with('success', 'Banner Added Successfully!');
    }

    public function emailSetting()
    {
        $emailSetting = EmailSetting::find(1);
        return view('admin.cms.email.email-setting', compact('emailSetting'));
    }

    public function postEmailSetting(Request $request)
    {
        $inputs = $request->all();

        $validator = Validator::make($inputs, [
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }
        $email = EmailSetting::where('id', $request->id)->first();

        if ($request->file('logo')) {

            $validator = Validator::make($inputs, [
                'logo' => 'required|image|mimes:jpeg,jpg,png|max:10000'
            ]);
            if ($validator->fails()) {
                return redirect()->back()->with('error', $validator->errors()->first());
            }

            $logo = time() . '_' . $request->file('logo')->getClientOriginalName();
            $request->file('logo')->move(public_path() . '/uploads/email/', $logo);

            $email->logo = $logo;
        } else {
            $logo = null;
        }

        if ($request->file('email_image')) {

            $validator = Validator::make($inputs, [
                'email_image' => 'required|image|mimes:jpeg,jpg,png|max:10000'
            ]);

            if ($validator->fails()) {
                return redirect()->back()->with('error', $validator->errors()->first());
            }


            $email_image = time() . '_' . $request->file('email_image')->getClientOriginalName();
            $request->file('email_image')->move(public_path() . '/uploads/email/', $email_image);

            $email->image = $email_image;
        } else {
            $email_image = null;
        }

        $email->content = $request->get('description');
        $email->save();

        return redirect()->back()->with('success', 'Email Template Updated!');
    }
}
