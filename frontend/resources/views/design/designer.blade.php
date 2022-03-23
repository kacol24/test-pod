@extends('layouts.layout')

@section('content')
    <div class="container container--app">
        @include('partials.product-nav')
        <h1 class="page-title font-size:22">
            Design Your Product
        </h1>
        <div class="font-size:14">
            Tweak and finalize the design and price of this product
        </div>
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{$error}}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endforeach
        <div class="row mt-4">
            <div class="col-md-4">
                <form id="form-design" method="post" action="{{route('design.post', [$masterproduct->id, 'edit' => request()->has('edit')])}}">
                    {{ csrf_field() }}
                    <div class="p-3 p-md-4" style="background: #F6F7F9;">
                        <div class="card p-0">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <div class="text-nowrap mr-3">
                                    <i class="fas fa-fw fa-image"></i>
                                    <h5 class="card-title d-inline-block">
                                        Template
                                    </h5>
                                </div>
                            </div>
                            <div class="card-body pt-3">
                                <div class="row">
                                    <div class="col-md">
                                        <select name="template" class="form-control">
                                            @foreach($templates as $template)
                                                <option value="{{$template->id}}">{{$template->design_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @php($option = $masterproduct->options->reverse()->first())
                        <div class="card p-0 mt-3"
                             x-data='{
                                variants: @json($option->details->pluck('key')),
                                selectAll: true,
                                toggleSelectAll: function() {
                                    if (this.selectAll) {
                                        this.variants = [];
                                    } else {
                                        this.variants = @json($option->details->pluck('key'));
                                    }
                                }
                             }'>
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <div>
                                    <i class="fas fa-fw fa-cubes"></i>
                                    <h5 class="card-title d-inline-block">
                                        Variants
                                    </h5>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="inlineCheckbox1"
                                           @click="toggleSelectAll()"
                                           x-model="selectAll">
                                    <label class="form-check-label" for="inlineCheckbox1">
                                        All Variants
                                    </label>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach($option->details as $detail)
                                        <div class="col-md-6">
                                            <div
                                                class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
                                                <div class="form-check w-100">
                                                    <input class="form-check-input" name="variants[]" checked="checked"
                                                           type="checkbox" value="{{$detail->key}}" x-model="variants"
                                                           id="{{$detail->key}}">
                                                    <label class="form-check-label d-flex justify-content-between align-items-center" for="{{$detail->key}}">
                                                        {{$detail->title}}
                                                        <span class="color-display" style="width: 20px;height: 20px;background-color:{{ optional($masterproduct->colors->firstWhere('lowercase_name', strtolower($detail->title)))->color }}"></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="card p-0 mt-3">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <div class="text-nowrap mr-3">
                                    <i class="fas fa-fw fa-money-bill"></i>
                                    <h5 class="card-title d-inline-block">
                                        Pricing
                                    </h5>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table m-0">
                                        <thead>
                                        <tr class="text-nowrap text-uppercase">
                                            <th>
                                                Variant
                                            </th>
                                            <th>
                                                You Buy At
                                            </th>
                                            <th>
                                                You Sell At
                                            </th>
                                            <th>
                                                Your Profit
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php($option = $masterproduct->options->first())
                                        @foreach($option->details as $detail)
                                            @php($sellingPrice = session('design')->firstWhere('master_product_id', $masterproduct->id)['selling_price'][$detail->key] ?? $detail->relatedSkus($masterproduct->id)->selling_price)
                                            <tr class="text-nowrap"
                                                x-data='{
                                                    detail: @json($detail),
                                                    sku: @json($detail->relatedSkus($masterproduct->id)),
                                                    sellingPrice: "{{ number_format($sellingPrice, 0, ',', '.') }}",
                                                    originalSellingPrice: "{{ $sellingPrice }}",
                                                    formattedSellingPrice: "{{ number_format($sellingPrice, 0, ',', '.') }}"
                                                }'>
                                                <th class="text-color:black font-size:12 fw-600">
                                                    {{ $detail->title }}
                                                </th>
                                                <td class="text-end">
                                                    {{ number_format($detail->relatedSkus($masterproduct->id)->base_cost, 0, ',', '.') }}
                                                </td>
                                                <td>
                                                    <input type="tel" class="form-control text-end"
                                                           aria-label="you sell at"
                                                           x-model="sellingPrice" x-ref="priceInput"
                                                           @focus="sellingPrice = originalSellingPrice; $nextTick(function() { $refs.priceInput.select() })"
                                                           @input.lazy="formattedSellingPrice = number_format(sellingPrice, 0, ',', '.'); originalSellingPrice = sellingPrice"
                                                           @blur="originalSellingPrice = sellingPrice; sellingPrice = formattedSellingPrice">
                                                    <input type="hidden" name="selling_price[{{ $detail->key }}]" x-model="originalSellingPrice">
                                                </td>
                                                <td class="text-end text-color:green"
                                                    x-text="number_format(originalSellingPrice - sku.base_cost, 0, ',', '.')">
                                                    100,000
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="card p-0 mt-3 border-0" style="background-color: transparent;">
                            <hr>
                            <div class="d-flex justify-content-between mb-3">
                                <a href="" class="text-color:blue text-decoration-none font-size:12">
                                    <i class="fas fa-arrow-left fa-fw"></i>
                                    Back
                                </a>
                            </div>
                            <input type="hidden" name="master_product_id" value="{{$masterproduct->id}}"/>
                            <input type="hidden" name="state_id" value="{{ $existingDesign['state_id'] }}"/>
                            <input type="hidden" name="print_file" value="{{ $existingDesign['print_file'] }}"/>
                            <input type="hidden" name="proof_file" value="{{ $existingDesign['proof_file'] }}"/>
                            <button type="button" id="continue" class="btn btn-primary w-100">
                                Continue
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md">
                <div class="card p-0">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title d-inline-block">
                            Product Colors
                        </h5>
                    </div>
                    <div class="card-body border-0 px-0 pb-0 pt-3">
                        @if($masterproduct->colors->count()>0)
                            <div class="d-flex flex-wrap mb-3 justify-content-center">
                                @foreach($masterproduct->colors as $color)
                                    <a href="javascript:void(0);"
                                       class="d-flex align-items-center color-option mx-1 mb-1 {{ $loop->first ? 'active' : '' }}"
                                       data-id="{{$color->id}}">
                                        <div class="color-display"
                                             style="background-color:{{$color->color}};"></div>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                        <div class="position-relative">
                            <iframe class="content__iframe" id="editorFrame"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{asset('backend/js/jquery.priceformat.min.js')}}"></script>
    <script src="{{asset('backend/js/number_format.js')}}"></script>
    <style>
        .content__iframe {
            height: 100%;
            width: 100%;
            min-height: calc(100vh - 76px);
        }

        .color-option {
            font-family: Poppins;
            font-style: normal;
            font-weight: normal;
            font-size: 12px;
            line-height: 22px;
            color: #000000;
            text-decoration: none;
        }

        .color-display {
            border-radius: 50%;
            border: 1px solid #D8DCE6;
            box-shadow: 0 1px 1px rgba(22, 29, 37, 0.05), inset 0 2px 0 rgba(255, 255, 255, 0.05);
            width: 30px;
            height: 30px;
            margin-right: 10px;
        }

        .color-option.active .color-display {
            border-color: black;
            border-width: 2px;
        }
    </style>
    <link href="https://canvas.printerous.com/production/Canvas/Edge/Configuration/customCss/loader.css"
          rel="stylesheet"/>
    <script id="CcIframeApiScript" type="text/javascript"
            src="https://canvas.printerous.com/production/Canvas/Edge/Resources/Generated/IframeApi.js"></script>

    <script type="text/javascript">
        let user_id = "{{session('current_store')->id}}";
        let redirect_type = 'editor';
        let state_id;
        let final_product;
        let shape;
        let safety_line;
        let bleed;
        let config;
        let surfaces;
        let editor;
        let mockup;
        let color_id;
        let template_id = $('select[name=\'template\']').val();
        var templates = {!!json_encode($templates)!!};
        var mockup_colors = {!!json_encode($masterproduct->mockupcolors)!!};

        @if($existingDesign['state_id'])
            state_id = "{{ $existingDesign['state_id'] }}";
        @endif

            @if($masterproduct->colors->count()>0)
            color_id = {{$masterproduct->colors->first()->id}};
        @endif

        function mmToPoint(mm) {
            var inch = mm / 25.4;
            var point = 72 * inch;
            console.log(point);
            return point;
        }

        $(function() {
            defineTemplate(template_id);

            $('select[name=\'template\']').change(function() {
                template_id = $(this).val();
                defineTemplate($(this).val());
            });

            $('.color-option').click(function() {
                color_id = $(this).attr('data-id');
                $('.color-option').removeClass('active');
                $(this).addClass('active');
                defineTemplate(template_id);
            });
        });

        function defineTemplate(id) {
            templates.map(function(el, idx) {
                if (el.id == id) {
                    surfaces = [];
                    mockups = [];
                    return setupEditor(el);
                }
            });
        }

        function setupEditor(template) {
            template.designs.map(function(el, idx) {
                var designLocation = JSON.parse(el.design_location);
                if (mockup_colors.length) {
                    mockup_colors.map(function(color, idx) {
                        if (color.color_id == color_id && color.design_id == el.id) {
                            mockup = color.customer_canvas;
                        }
                    });
                } else {
                    mockup = el.mockup_customer_canvas;
                }
                surfaces.push({
                    name: el.page_name,
                    printAreas: [
                        {
                            designFile: el.customer_canvas,
                            designLocation: {X: designLocation.X, Y: designLocation.Y}
                        }
                    ],
                    mockup: {
                        down: mockup
                    },
                    proofImage: {
                        fileFormat: 'PNG',
                        rgbColorProfileName: 'Adobe RGB (1998)',
                        mockupEnabled: false
                    }
                });
            });

            let product_definition = {
                surfaces: surfaces
            };

            shape = template.shape;
            safety_line = parseFloat(template.safety_line);
            bleed = parseFloat(template.bleed);

            config = {
                initialMode: 'Advanced',
                userId: user_id,
                customStyle: 'toolbox-icon',
                canvas: {
                    containerColor: '#E5E5E5',
                    canvasItemHoverEnabled: true,
                    violationWarningButtonsEnabled: true,
                    qualityChangeContainersEnabled: true
                },
                widgets: {
                    ObjectInspector: {
                        variableItemsEnabled: true,
                        showItemName: true
                    },
                    ItemMenu: {
                        renameEnabled: true
                    },
                    Toolbox: {
                        buttons: [
                            {
                                translationKey: 'Toolbox.TEXT',
                                translationKeyTitle: 'Toolbox.TITLE_ADD_TEXT',
                                iconClass: 'prs-icon prs-icon--caption prs-icon-add-text',
                                buttons: [
                                    {
                                        translationKey: 'Toolbox.TEXT',
                                        translationKeyTitle: 'Toolbox.TITLE_ADD_TEXT',
                                        iconClass: 'prs-icon prs-icon-add-text',
                                        action: 'Text'
                                    },
                                    'RichText'
                                ]
                            },
                            {
                                translationKey: 'Toolbox.IMAGE',
                                translationKeyTitle: 'Toolbox.TITLE_ADD_IMAGE',
                                iconClass: 'prs-icon prs-icon--caption prs-icon-add-image',
                                action: 'Image'
                            },
                            {
                                translationKey: 'Toolbox.SHAPE',
                                translationKeyTitle: 'Toolbox.TITLE_ADD_SHAPE',
                                iconClass: 'prs-icon prs-icon--caption prs-icon-add-shape',
                                buttons: ['Line', 'Rectangle', 'Ellipse']
                            },
                            {
                                translationKey: 'Toolbox.LINEAR_BARCODE',
                                translationKeyTitle: 'Toolbox.TITLE_ADD_LINEAR_BARCODE',
                                iconClass: 'prs-icon prs-icon--caption prs-icon-add-qr',
                                buttons: ['LinearBarcode', 'QrCode']
                            }
                        ]
                    }
                },

                rendering: {
                    hiResOutputDpi: 300
                },
                violationWarningsSettings: {
                    qualityMeter: {
                        enabled: true,
                        qualityLevels: {
                            warning: '99',
                            bad: '50'
                        }
                    }
                }
            };

            let cc_default_product_config = {};
            if (safety_line > 0) {
                if (shape == 'square') {
                    cc_default_product_config = {
                        defaultSafetyLines: [
                            {
                                margin: {
                                    horizontal: mmToPoint(bleed),
                                    vertical: mmToPoint(bleed)
                                },
                                color: 'rgba(255,0,0,255)',
                                altColor: 'rgba(255,0,0,255)',
                                stepPx: 5,
                                widthPx: 1
                            }
                        ]
                    };
                } else if (shape == 'circle') {
                    cc_default_product_config = {
                        defaultSafetyLines: [
                            {
                                margin: {
                                    horizontal: mmToPoint(bleed),
                                    vertical: mmToPoint(bleed)
                                },
                                borderRadius: '100%',
                                color: 'rgba(255,0,0,255)',
                                altColor: 'rgba(255,0,0,255)',
                                stepPx: 5,
                                widthPx: 1
                            }
                        ]
                    };
                }
            }

            if (state_id) {
                final_product = state_id;
            } else {
                final_product = $.extend(product_definition, cc_default_product_config);
            }

            const ResizeRectangleType = {
                /** Fits the original bounding rectangle into the resulting one and maintains its proportions. */
                Fit: 0,
                /** Fills the original bounding rectangle into the resulting one. */
                Fill: 1,
                /** Applies an arbitrary resize to items to fit them into the resulting bounding rectangle. */
                Arbitrary: 2,
                /** Expands only the canvas. Items remain in their places in the center of the expanded canvas. */
                Original: 3
            };

            editor = CustomersCanvas.IframeApi.loadEditor(
                document.getElementById('editorFrame'),
                final_product,
                config
            ).then(function(editor) {
                $('#continue').click(function() {
                    editor.finishProductDesign().then(function(result) {
                        // Verify a state ID and a user ID.
                        stateId = result.stateId;
                        userId = result.userId;
                        // Get links to hi-res outputs.
                        hiResOutputUrls = result.hiResOutputUrls;
                        $('input[name=\'state_id\']').val(stateId);
                        $('input[name=\'print_file\']').val(hiResOutputUrls[0]);
                        $('input[name=\'proof_file\']').val(result.proofImageUrls);
                        $('#form-design').submit();
                    });
                });
            });

            var idxSurface = 0;
            var selectItem = function(editor, product) {
                product.switchTo(product.surfaces[idxSurface]).then(function(resp) {
                    let args = {
                        targetType: 'surfaces',
                        targetIds: [product.surfaces[idxSurface].id],
                        width: '255',
                        height: '156',
                        defaultOptions: {
                            resize: 2,
                            resetPlaceholderContent: false
                        }
                    };

                    let args_with_safety = {
                        targetType: 'surfaces',
                        targetIds: [product.surfaces[idxSurface].id],
                        width: '272',
                        height: '173',
                        defaultOptions: {
                            resize: 3,
                            resetPlaceholderContent: false
                        },
                        containerOptions: {
                            Background: {
                                resize: 2
                            }
                        }
                    };

                    var need_resize = false;
                    if (need_resize) {
                        if (!state_id) {
                            editor.commandManager.execute('resize', args);
                        }
                    }
                    if (safety_line > 0) {
                        if (!state_id) {
                            editor.commandManager.execute('resize', args_with_safety);
                        }
                    }

                    editor.selectItems([
                        {
                            name: 'BG'
                        }
                    ]);

                    editor.getSelectedItems().then(function(items) {
                        if (items.length) {
                            var itemIds = [];
                            items.forEach(function(item) {
                                itemIds.push(item.id);
                            });

                            //resize
                            let resize_p = {
                                targetType: 'items',
                                targetIds: itemIds,
                                width: '272',
                                height: '173',
                                defaultOptions: {
                                    resize: 2,
                                    resetPlaceholderContent: false
                                },
                                containerOptions: {
                                    Background: {
                                        resize: 2
                                    }
                                }
                            };
                            if (!state_id) {
                                editor.commandManager.execute('resize', resize_p);
                            }
                        } else {
                            console.log('There are no selected items.');
                        }

                        idxSurface++;
                        setTimeout(function() {
                            if (idxSurface < product.surfaces.length) {
                                selectItem(editor, product);
                            } else {
                                product.switchTo(product.surfaces[0]);
                                $('.overlay-element').remove();
                            }
                        }, 1000);
                    });
                });
            };
        }
    </script>
@endpush
