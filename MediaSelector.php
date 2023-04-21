<?php

namespace App\PowerUps\Components\MediaSelector;

use Livewire\Component;
use Livewire\WithFileUploads;
use Validator;

class MediaSelector extends Component
{
    use WithFileUploads;

    public $emoji;

    public $image_string;

    public $image_url;

    public $imageUpload;

    public $type;

    public $height = '300px';

    public $sections = ['emoji', 'upload', 'link'];

    public $section = 'emoji';

    public $submit_processing = false;

    public $eventCallback;

    public $recommended;

    protected $image_upload_rules = ['imageUpload' => 'image|mimes:jpeg,jpg,png,bmp,gif,svg|max:30000'];

    protected $image_upload_rules_non_pro = ['imageUpload' => 'image|mimes:jpeg,jpg,png,bmp,gif,svg|max:1024'];
    //'image_upload' => 'image|max:5120'];

    protected $listeners = ['mediaSelectorImageUpload'];

    const SELECTOR_TYPE_EMOJI = 'emoji';

    const SELECTOR_TYPE_IMAGE_URL = 'image_url';
    // const SELECTOR_TYPE_IMAGE_URL = 'image_url';

    public function mount($media = '')
    {
        if (! is_array($this->sections)) {
            $this->sections = explode(',', $this->sections);
        }
        if (isset($this->type)) {
            $this->section = $this->type;
        } else {
            $this->type = 'emoji';
        }

        if (isset($this->type) && $this->type == 'emoji') {
            $this->emoji = $media;
        } else {
            $this->image_string = $media;
        }
    }

    public function isStringHasEmojis($string)
    {
        $emojis_regex =
            '/[\x{0080}-\x{02AF}'
            .'\x{0300}-\x{03FF}'
            .'\x{0600}-\x{06FF}'
            .'\x{0C00}-\x{0C7F}'
            .'\x{1DC0}-\x{1DFF}'
            .'\x{1E00}-\x{1EFF}'
            .'\x{2000}-\x{209F}'
            .'\x{20D0}-\x{214F}'
            .'\x{2190}-\x{23FF}'
            .'\x{2460}-\x{25FF}'
            .'\x{2600}-\x{27EF}'
            .'\x{2900}-\x{29FF}'
            .'\x{2B00}-\x{2BFF}'
            .'\x{2C60}-\x{2C7F}'
            .'\x{2E00}-\x{2E7F}'
            .'\x{3000}-\x{303F}'
            .'\x{A490}-\x{A4CF}'
            .'\x{E000}-\x{F8FF}'
            .'\x{FE00}-\x{FE0F}'
            .'\x{FE30}-\x{FE4F}'
            .'\x{1F000}-\x{1F02F}'
            .'\x{1F0A0}-\x{1F0FF}'
            .'\x{1F100}-\x{1F64F}'
            .'\x{1F680}-\x{1F6FF}'
            .'\x{1F910}-\x{1F96B}'
            .'\x{1F980}-\x{1F9E0}]/u';
        preg_match($emojis_regex, $string, $matches);

        return ! empty($matches);
    }

    public function updatedImageUpload()
    {
        // if user is pro we can validate with the default upload rules
        if (auth()->user() && auth()->user()->isPro()) {
            $validator = Validator::make($this->getDataForValidation($this->image_upload_rules), $this->image_upload_rules);

            if ($validator->fails()) {
                $this->dispatchBrowserEvent('notificationError', [
                    'message' => $validator->errors()->first(),
                ]);

                return;
            }
        } else {
            // otherwise we want to validate with the free user upload rules
            $validator = Validator::make($this->getDataForValidation($this->image_upload_rules_non_pro), $this->image_upload_rules_non_pro);

            if ($validator->fails()) {
                $this->dispatchBrowserEvent('notificationError', [
                    'message' => $validator->errors()->first().' - upgrade to increase file size limit',
                ]);

                return;
            }
        }

        $url = $this->imageUpload->store('assets', 'public');

        $this->dispatchBrowserEvent('image-upload-complete', [
            'url' => $url,
        ]);
    }

    public function mediaSelectorImageUpload()
    {
        $validator = Validator::make($this->getDataForValidation($this->image_upload_rules), $this->image_upload_rules);

        if ($validator->fails()) {
            $this->dispatchBrowserEvent('notificationError', [
                'message' => 'Image upload invalid',
            ]);

            return;
        }

        $url = $this->image_upload->store('assets', 'public');
    }

    public function uploadImage()
    {
        $this->validate([
            'image_upload' => 'image|max:1024', // 1MB Max
        ]);

        $url = $this->image_upload->store('assets', 'public');


        $this->dispatchBrowserEvent('notificationSuccess', [
            'message' => 'Successfully uploaded image',
        ]);
    }

    public function render()
    {
        return view('powerups::mediaselector.media-selector');
    }
}
