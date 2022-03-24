<?php

namespace App\Jobs;

use App\Models\CanvasLog;
use App\Models\Product\Color;
use App\Models\Product\Design;
use App\Models\Product\MockupColor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GenerateMockupColors implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $color;
    protected $design;
    protected $product;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($color, $design, $product)
    {
        $this->color = $color;
        $this->design = $design;
        $this->product = $product;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $design = $this->design;
        $color = $this->color;
        $mockupImageUrl = $this->uploadMockupColor($design, $color);

        if (! $mockupImageUrl) {
            return;
        }

        $mockupImage = file_get_contents($mockupImageUrl);
        $mockupFilename = substr($mockupImageUrl, strrpos($mockupImageUrl, '=') + 1);

        $path = storage_path('app/b2b2c/mockups');
        if (! file_exists($path)) {
            mkdir($path, 0755, true);
        }
        Storage::put('b2b2c/mockups/'.$mockupFilename, $mockupImage);

        MockupColor::updateOrCreate([
            'color_id'  => $color->id,
            'design_id' => $design->id,
        ], [
            'customer_canvas' => $this->uploadCanvas(Storage::url('b2b2c/mockups/'.$mockupFilename), 'mockups'),
            'product_id'      => $this->product->id,
        ]);
    }

    private function uploadMockupColor(Design $design, Color $color)
    {
        if (! $design->mockup_customer_canvas) {
            return false;
        }

        $key = 'PrinterousCustomerCanvasDemo123!@#';
        $url = 'https://canvas.printerous.com/production/DI/api/rendering/preview?disableCache=true';
        $post = [
            'template' => $design->mockup_customer_canvas,
            'format'   => 'png',
            'size'     => [
                'width'  => $design->mockup_width,
                'height' => $design->mockup_height,
            ],
            'data'     => [
                'Color' => [
                    'type'  => 'shape',
                    'color' => $color->color,
                ],
            ],
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "X-CustomersCanvasAPIKey: ".$key,
            'Content-Type: application/json',
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $log = CanvasLog::create([
            'type' => static::class . '::uploadMockupColor',
            'request' => json_encode([
                $url,
                $key,
                $post,
            ])
        ]);

        $result = curl_exec($ch);

        $log->update([
            'response' => json_encode($result)
        ]);

        curl_close($ch);

        return Str::of($result)->trim('"');
    }

    private function uploadCanvas($file, $type)
    {
        $key = 'PrinterousCustomerCanvasDemo123!@#';
        $url = "https://canvas.printerous.com/production/Canvas/Edge/api/ProductTemplates/".$type."/pod";

        $name = ($type == 'designs') ? 'design' : 'preview_mockups';
        $post = [$name => curl_file_create($file)];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "X-CustomersCanvasAPIKey: ".$key,
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $log = CanvasLog::create([
            'type' => static::class . '::uploadCanvas',
            'request' => json_encode([
                $url,
                $key,
                $post,
            ])
        ]);

        $result = curl_exec($ch);

        $log->update([
            'response' => json_encode($result)
        ]);

        curl_close($ch);

        return Str::of($result)->trim('"');
    }
}
