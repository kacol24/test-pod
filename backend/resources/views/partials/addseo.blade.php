<div class="card card--widget p-0">
  <div class="card-header border-0"
       style="background: rgba(99,195,235,0.05);">
      <div class="row flex-fill justify-content-between">
          <div class="col-md">
              <div class="d-flex align-items-center">
                  <img alt="" class="card-title-icon img-fluid"
                       src="{{asset('images/icons/icon_customers.png')}}">
                  <h5 class="card-title">
                      <small class="d-block">
                          The title tag and meta description are two of the most important elements of
                          SEO. They are shown in search engine results, and provide information to
                          people wo are looking for things related to your products. A good title and
                          description encourages customers to click the link ion search results to
                          visit your store.
                      </small>
                  </h5>
              </div>
          </div>
      </div>
  </div>
  <div class="card-body">
      <div class="form-group">
          <label class="text-uppercase" for="meta_title">Page Title</label>
          <input type="text" class="form-control" name="meta_title" id="meta_title" placeholder="Page Title" value="{{old('meta_title')}}">
      </div>

      <div class="form-group">
          <label class="text-uppercase"for="meta_keyword">Meta Keyword (Separated By ,)</label>
          <input type="text" class="form-control" name="meta_keyword" id="meta_keyword" placeholder="Meta Keyword" value="{{old('meta_keyword')}}">
      </div>

      <div class="form-group">
          <label class="text-uppercase" for="meta_description">Meta Description</label>
          <textarea class="form-control" name="meta_description" id="meta_description" rows="6" placeholder="Meta Descriptions">{{old('meta_description')}}</textarea>
      </div>
  </div>
</div>