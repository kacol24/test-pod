<div class="stepper mb-5 sticky-top sticky-top--header">
    <h3 class="stepper__title text-uppercase d-none d-md-block">
        Create Product
    </h3>
    <div class="stepper__steps">
        <a href="{{ route('products.create') }}"
           class="stepper__step {{ request()->routeIs(['products.*']) ? 'stepper__step--stepped' : '' }}">
            <div class="stepper__numbering">1</div>
            <div class="stepper__step-content">
                <div class="d-none d-md-block">
                    Select Product
                </div>
            </div>
        </a>
        <a href="{{ route('products.designer') }}"
           class="stepper__step {{ request()->routeIs(['products.designer']) ? 'stepper__step--stepped' : '' }}">
            <div class="stepper__numbering">2</div>
            <div class="stepper__step-content">
                <div class="d-none d-md-block">
                    Design Your Product
                </div>
            </div>
        </a>
        <a href="#" class="stepper__step">
            <div class="stepper__numbering">3</div>
            <div class="stepper__step-content">
                <div class="d-none d-md-block">
                    Add More Products
                </div>
            </div>
        </a>
        <a href="#" class="stepper__step">
            <div class="stepper__numbering">4</div>
            <div class="stepper__step-content">
                <div class="d-none d-md-block">
                    Set Product Details
                </div>
            </div>
        </a>
    </div>
</div>
