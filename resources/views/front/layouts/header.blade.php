<!-- Navigation -->
<nav class="navbar navbar-expand-lg">
  <div class="container">
     <a class="navbar-brand" href="/"><img src="{{ asset('uploads/Bentonville%20(2)%20(2).png')}}" alt="{!! asset($setting_data['website']) !!}"></a> 
    <button
      class="navbar-toggler"
      type="button"
      data-bs-toggle="collapse"
      data-bs-target="#navbarNav"
    >
      <span class="navbar-toggler-icon" style="color: white;"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="{{url('/')}}" >
            Home
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{url('properties')}}">Book Your Stay</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="{{url('attractions')}}">Attractions</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{url('partner')}}">Partners</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{url('property-management')}}">Management</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{url('services')}}">Development Services</a>
        </li>
      </ul>
      <!-- <button class="btn btn-book ms-3">BOOK YOUR STAY</button> -->
    </div>
  </div>
</nav>
<style>
    /* Header/Navigation Styles */
.navbar {
    background-color: var(--highlight-bg);
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}
.navbar-toggler {
    background-color: #ffffff;
}
.navbar-brand {
    font-family: 'Playfair Display', serif;
    font-weight: 600;
    color: var(--text-dark) !important;
    font-size: 1.6rem;
    letter-spacing: 2px;
}

.navbar-brand img {
    /*max-height: 80px;*/
    height: auto;
    width: 100px;
    transition: all 0.3s ease;
    white-space: nowrap;
}

.navbar-nav .nav-link {
    color: var(--text-light) !important;
    font-weight: 500;
    margin: 0 6px;
    transition: all 0.3s ease;
    position: relative;
    font-size: 1.12rem;
}

.navbar-nav .nav-link:hover {
    color: var(--grey-dark) !important;
}

.navbar-nav .nav-link:after {
    content: '';
    position: absolute;
    bottom: -3px;
    left: 0;
    width: 0;
    height: 1px;
    background: var(--grey-dark);
    transition: width 0.3s ease;
}

.navbar-nav .nav-link:hover:after {
    width: 100%;
}


.navbar-collapse {
    flex-basis: 100%;
    flex-grow: 1;
    align-items: center;
    justify-content: end;
    display: flex;
}

.btn-book {
    background-color: var(--cta-button);
    color: var(--cta-text);
    border: none;
    padding: 10px 25px;
    border-radius: 3px;
    font-weight: 500;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-size: 0.85rem;
}

.btn-book:hover {
    background-color: var(--primary-accent);
    color: var(--text-light);
    transform: translateY(-2px);
}

@media (max-width: 768px) {
    .navbar-brand img {
        max-height: 60px;
        width: 75px;
    }

    .navbar-nav .nav-link {
        font-size: 1rem;
        padding: 10px 0;
    }
    
.navbar-collapse {
    flex-basis: 100%;
    flex-grow: 1;
    align-items: start;
    justify-content: start;
    display: flex;
}

}

</style>
