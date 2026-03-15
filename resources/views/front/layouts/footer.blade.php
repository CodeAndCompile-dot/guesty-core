<!-- Footer -->
<footer class="footer">
  <div class="container">
    <div class="footer-content">
      <div class="footer-logo">
        <img src="{!! asset($setting_data['footer_logo']) !!}" alt="{!! asset($setting_data['website']) !!}" />
      </div>

      <div class="footer-section">
        <h4>REACH OUT ANY TIME</h4>
        <ul>
          
          <!-- <li><a href="mailto:rentals@redcottage.com">rentals@redcottage.com</a></li> -->
          <li>
            <a href="mailto:{!! $setting_data['email'] ?? '#' !!}">
              {!! $setting_data['email'] ?? '#'!!}</a>
          </li>
          <li>
            <a href="mailto:479-329-4563
">
              479-329-4563
</a>
          </li>
          <!--<li><a href="tel:{!! $setting_data['mobile'] ?? '#' !!}">{!! $setting_data['mobile'] ?? '#'!!}</a></li>-->
          <!-- <li><a href="#">Careers</a></li>
                    <li><a href="#">Agents</a></li> -->
        </ul>
        <h4 class="mt-4">FOLLOW US</h4>
        <ul>
          <li><a href="{!! $setting_data['facebook'] ?? '#' !!}">Facebook</a></li>
          <li><a href="{!! $setting_data['instagram'] ?? '#' !!}">Instagram</a></li>
        </ul>
      </div>

      <div class="footer-section">
        <h4>QUICK LINKS</h4>
        <ul>
          <li><a href="{{url('/')}}">Home</a></li>
          <li><a href="{{url('properties')}}">Book Your Stay</a></li>
          <li><a href="{{url('attractions')}}">Attractions</a></li>
          <li><a href="{{url('partner')}}">Partners</a></li>
          <li><a href="{{url('property-management')}}">Management</a></li>
          <li><a href="{{url('services')}}">Development Services</a></li>
        </ul>
      </div>

      <div class="footer-section">
        <h4>Our Properties</h4>
        <ul>
            @php
               $property= App\Models\Guesty\GuestyProperty::where(["status"=>"true","active"=>"1"])->orderBy("id","desc")->limit(5)->get();
            @endphp
            @foreach ($property as $p)
                <li><a href="{{url($p->seo_url)}}">{{$p->title}}</a></li>
            @endforeach
          <li>
            <a href="{{url('properties')}}" style="text-decoration: underline"
              >View All <i class="fas fa-arrow-right"></i
            ></a>
          </li>
        </ul>
      </div>
    </div>

    <div class="footer-bottom">
      <p>
          © 2026 Bentonville Lodging Co - An Above 8 
        <!--© {!! $setting_data['copyright'] ?? '#' !!} All Rights Reserved. |-->
        <a href="#" style="color: var(--grey-mid); text-decoration: none">Capital LLC Brand</a> | <a href="/privacy" style="color: var(--grey-mid); text-decoration: none">Rental Agreement and Policies</a>
      </p>
    </div>
  </div>
</footer>
<style>
    /* Footer Styles */
.footer {
    background: var(--primary-accent);
    color: var(--text-light);
    padding: 80px 0 30px;
}

.footer-content {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr 1fr;
    gap: 50px;
    margin-bottom: 50px;
}

.footer-logo {
    grid-column: 1;
}

.footer-logo img {
    max-height: 100px;
    margin-bottom: 20px;
}

.footer-logo-text {
    font-family: 'Playfair Display', serif;
    font-weight: 600;
    font-size: 1.8rem;
    margin-bottom: 15px;
}

.footer-tagline {
    color: var(--grey-mid);
    margin-bottom: 20px;
    font-size: 0.95rem;
}

.footer-section h4 {
    color: var(--text-light);
    margin-bottom: 10px;
    font-weight: 500;
    font-size: 1.1rem;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.footer-section ul {
    list-style: none;
    padding-left: 0;
}

.footer-section ul li {
    margin-bottom: 12px;
}

.footer-section ul li a {
    color: var(--grey-mid);
    text-decoration: none;
    transition: all 0.3s ease;
    font-size: 0.95rem;
}

.footer-section ul li a:hover {
    color: var(--text-light);
    padding-left: 5px;
}

.footer-social {
    display: flex;
    gap: 15px;
    margin-top: 20px;
}

.footer-social a {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--grey-mid);
    border: 1px solid var(--grey-mid);
    border-radius: 3px;
    transition: all 0.3s ease;
}

.footer-social a:hover {
    color: var(--text-light);
    border-color: var(--text-light);
    transform: translateY(-3px);
}

.footer-bottom {
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    padding-top: 25px;
    text-align: center;
    color: var(--grey-mid);
    font-size: 0.9rem;
}

@media (max-width: 768px) {
    .footer-content {
        grid-template-columns: 2fr 1fr;
        text-align: left;
    }

    .footer-social {
        justify-content: flex-start;
    }
}
@media (max-width: 576px) {
    .footer-content {
        grid-template-columns: 1fr;
        text-align: left;
        gap: 20px;
    }

    .footer-social {
        justify-content: flex-start;
    }
    
.footer {

    padding: 30px 0 30px;
}
}
</style>
@include("front.layouts.js")
@yield('js')
<script>
    $(document).on("submit",".newsletter-data",function(e){
      e.preventDefault();
      data=$(this).serialize();
      url=$(this).attr("action");
      $.post(url,data,function(data){
        if(data.status==200){
            $(".newsletter-data")[0].reset();
            toastr.success(data.message);
        }else{
          toastr.error(data.message);
        }
      });
    });
</script>

