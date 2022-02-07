<div class="drop-upload">
    <div class="drop-upload__container"
         data-sortable x-cloak
         x-data='dropUpload("{{ $route }}", {{ $images }})'>
        <template x-for="(image, index) in images" :key="image.filename">
            <div class="drop-upload__item">
                <div class="drop-upload__main-image-label">
                    <span class="badge badge-success">MAIN IMAGE</span>
                </div>
                <div class="drop-upload__item-container">
                    <img :src="image.images[options.imageKey]" :alt="image.filename" class="img-fluid">
                    <input type="hidden" name="images[]" x-model="image.filename">
                    <div class="drop-upload__action d-flex justify-content-center">
                        <a href="#" @click="removeImage(index)"
                           class="drop-upload__action-item text-color:red">
                            <i class="fas fa-fw fa-trash"></i>
                        </a>
                    </div>
                </div>
            </div>
        </template>
        <label for="drop-upload__input"
               class="drop-upload__item drop-upload__item--placeholder embed-responsive embed-responsive-1by1">
            <input type="file" id="drop-upload__input" class="drop-upload__input" multiple
                   x-ref="fileField" x-model="file"
                   @change="uploadImages($refs.fileField.files)">
        </label>
        <template x-if="isLoading">
            <div class="drop-upload__loading">
                <i class="fas fa-fw fa-2x fa-spinner fa-spin"></i>
            </div>
        </template>
    </div>
    <label for="drop-upload__input" class="drop-upload__label">
        {!!Lang::get('blog.clickdrag')!!}
    </label>
</div>
