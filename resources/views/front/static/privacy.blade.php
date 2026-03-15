@extends("front.layouts.master")
@section("title",$data->meta_title)
@section("keywords",$data->meta_keywords)
@section("description",$data->meta_description)
@section("logo",$data->image)
@section("header-section")
{!! $data->header_section !!}
@stop
@section("footer-section")
{!! $data->footer_section !!}
@stop
@section("container")
@php
$name=$data->name;
$bannerImage=asset('front/images/breadcrumb.webp');

@endphp
<style>
    /* Privacy Policy Page Styles */
    .privacy-hero {
        position: relative;
        height: 300px;
        background-color: var(--highlight-bg);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-light);
    }

    .privacy-hero::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: url("data:image/svg+xml,%3Csvg width='20' height='20' viewBox='0 0 20 20' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.05' fill-rule='evenodd'%3E%3Ccircle cx='3' cy='3' r='3'/%3E%3Ccircle cx='13' cy='13' r='3'/%3E%3C/g%3E%3C/svg%3E");
        opacity: 0.3;
        pointer-events: none;
        z-index: 1;
    }

    .privacy-logo {
        width: 100px;
        height: auto;
        margin-bottom: 20px;
    }

    .privacy-hero-content {
        position: relative;
        z-index: 2;
        text-align: center;
    }

    .privacy-title {
        font-size: 3rem;
        font-weight: 600;
        margin-top: 10px;
    }

    .privacy-content {
        padding: 60px 0;
        background-color: var(--background-light);
    }

    /* New two-column layout */
    .privacy-container {
        display: flex;
        flex-direction: row;
        gap: 30px;
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    /* Left sidebar with navigation */
    .privacy-nav {
        flex: 0 0 300px;
        position: sticky;
        top: 100px;
        height: calc(100vh - 120px);
        overflow-y: auto;
        padding-right: 20px;
        padding-top: 10px;
    }

    .privacy-nav-container {
        background-color: var(--text-light);
        border-radius: 8px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        padding: 25px;
    }

    .privacy-nav-title {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 1px solid var(--grey-light);
    }

    .privacy-nav-list {
        list-style-type: none;
        padding: 0;
        margin: 0;
    }

    .privacy-nav-item {
        margin-bottom: 12px;
    }

    .privacy-nav-link {
        display: block;
        padding: 8px 10px;
        color: var(--grey-dark);
        text-decoration: none;
        border-radius: 4px;
        transition: all 0.2s ease;
        font-size: 0.95rem;
    }

    .privacy-nav-link:hover, .privacy-nav-link.active {
        background-color: rgba(0,0,0,0.03);
        color: var(--text-dark);
        font-weight: 500;
    }

    /* Right content section */
    .privacy-sections {
        flex: 1;
        background-color: var(--text-light);
        border-radius: 8px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        padding: 30px;
    }

    .privacy-section {
       
        scroll-margin-top: 100px; /* For smooth scrolling to sections */
    }

    .privacy-section:first-of-type {
        padding-top: 0;
    }

    .privacy-section:last-child {
        margin-bottom: 0;
    }

    .privacy-section h2 {
        font-size: 1.75rem;
        font-weight: 500;
        margin-bottom: 15px;
        color: var(--text-dark);
       
        padding-bottom: 10px;
    }

    .privacy-section h2::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 60px;
        height: 3px;
        background-color: var(--grey-mid);
    }

    .privacy-section p {
        color: var(--grey-dark);
        line-height: 1.8;
        margin-bottom: 20px;
        font-size: 1rem;
    }

    .privacy-section ul {
        padding-left: 20px;
        margin-bottom: 20px;
    }

    .privacy-section li {
        color: var(--grey-dark);
        margin-bottom: 12px;
        line-height: 1.6;
    }

    .privacy-section a {
        color: var(--primary-accent);
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .privacy-section a:hover {
        text-decoration: underline;
    }

    /* Responsive styles */
    @media (max-width: 992px) {
        .privacy-container {
            flex-direction: column;
            
        }

        .privacy-nav {
           display: none;
        }
        
        .privacy-nav-container {
            padding: 15px;
        }
        
        .privacy-nav-list {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .privacy-nav-item {
            margin-bottom: 0;
        }
        
        .privacy-nav-link {
            padding: 5px 10px;
            font-size: 0.85rem;
            white-space: nowrap;
        }
    }

    @media (max-width: 768px) {
        .privacy-title {
            font-size: 2rem;
        }

        .privacy-section h2 {
            font-size: 1.5rem;
        }
        
        .privacy-sections {
            padding: 20px;
        }
        
        .privacy-nav-list {
            flex-wrap: nowrap;
            overflow-x: auto;
            padding-bottom: 10px;
            -webkit-overflow-scrolling: touch;
        }
    }

    @media (max-width: 576px) {
        .privacy-container {
            padding: 10px;
        }
        
       
    }
</style>

<!-- Privacy Policy Hero Section -->
<section class="privacy-hero">
    <div class="container">
        <div class="privacy-hero-content">
            <img src="{!! asset($setting_data['footer_logo']) !!}" alt="{!! asset($setting_data['website']) !!}" />
            <h1 class="privacy-title">SHORT-TERM RENTAL AGREEMENT</h1>
        </div>
    </div>
</section>

<!-- Privacy Policy Content Section -->
<section class="privacy-content">
    <div class="privacy-container">
        <!-- Left Side Navigation -->
        <div class="privacy-nav d-lg-block hidden">
            <div class="privacy-nav-container">
                <h3 class="privacy-nav-title">Agreement Contents</h3>
                <ul class="privacy-nav-list">
                    <li class="privacy-nav-item"><a href="#introduction" class="privacy-nav-link">Introduction</a></li>
                    <li class="privacy-nav-item"><a href="#parties" class="privacy-nav-link">1. The Parties</a></li>
                    <li class="privacy-nav-item"><a href="#property" class="privacy-nav-link">2. The Property</a></li>
                    <li class="privacy-nav-item"><a href="#termination" class="privacy-nav-link">3. Termination</a></li>
                    <li class="privacy-nav-item"><a href="#maintenance" class="privacy-nav-link">4. Maintenance and Repairs</a></li>
                    <li class="privacy-nav-item"><a href="#trash" class="privacy-nav-link">5. Trash</a></li>
                    <li class="privacy-nav-item"><a href="#pets" class="privacy-nav-link">6. Pets</a></li>
                    <li class="privacy-nav-item"><a href="#quiet" class="privacy-nav-link">7. Quiet Enjoyment</a></li>
                    <li class="privacy-nav-item"><a href="#smoking" class="privacy-nav-link">8. Smoking</a></li>
                    <li class="privacy-nav-item"><a href="#liability" class="privacy-nav-link">9. Liability</a></li>
                    <li class="privacy-nav-item"><a href="#deposit" class="privacy-nav-link">10. Rental Deposit</a></li>
                    <li class="privacy-nav-item"><a href="#fees" class="privacy-nav-link">11. Attorney's Fees</a></li>
                    <li class="privacy-nav-item"><a href="#use" class="privacy-nav-link">12. Use of Property</a></li>
                    <li class="privacy-nav-item"><a href="#stays" class="privacy-nav-link">13. Shortened Stays</a></li>
                    <li class="privacy-nav-item"><a href="#firearms" class="privacy-nav-link">14. Firearms</a></li>
                    <li class="privacy-nav-item"><a href="#fireworks" class="privacy-nav-link">15. Fireworks</a></li>
                    <li class="privacy-nav-item"><a href="#illegal" class="privacy-nav-link">16. Illegal Use</a></li>
                    <li class="privacy-nav-item"><a href="#possessions" class="privacy-nav-link">17. Possessions</a></li>
                    <li class="privacy-nav-item"><a href="#tv" class="privacy-nav-link">18. TV & Streaming</a></li>
                    <li class="privacy-nav-item"><a href="#internet" class="privacy-nav-link">19. Internet</a></li>
                    <li class="privacy-nav-item"><a href="#cancellation" class="privacy-nav-link">20. Cancellation Policy</a></li>
                    <li class="privacy-nav-item"><a href="#law" class="privacy-nav-link">21. Governing Law</a></li>
                    <li class="privacy-nav-item"><a href="#acknowledgement" class="privacy-nav-link">Acknowledgement</a></li>
                </ul>
            </div>
        </div>
        
        <!-- Right Side Content -->
        <div class="privacy-sections">
            <div id="introduction" class="privacy-section">
                <p>This Short-Term Rental Agreement ("Agreement") is made on the date of the annotated reservation via Airbnb, Guesty, or VRBO between:</p>
                <ul>
                    <li>Tenant: The registered guest associated with the reservation.</li>
                    <li>Landlord: Bentonville Lodging Co LLC.</li>
                </ul>
            </div>

            <div id="parties" class="privacy-section">
                <h2>1. The Parties</h2>
                <p>This Agreement is entered into between the registered guest ("Tenant") during valid reservation dates and Bentonville Lodging Co LLC ("Landlord").</p>
            </div>

            <div id="property" class="privacy-section">
                <h2>2. The Property</h2>
                <p>The property is defined as the registered location associated with the active reservation.</p>
            </div>

            <div id="termination" class="privacy-section">
                <h2>3. Termination</h2>
                <ul>
                    <li>The Landlord may inspect the premises with prior notice in accordance with applicable State laws.</li>
                    <li>Violation of this Agreement or house rules results in immediate termination of the rental period.</li>
                    <li>Tenants waive all rights to process if they fail to vacate the premises upon termination.</li>
                    <li>Tenants must vacate the premises by the registered checkout date and time.</li>
                </ul>
            </div>

            <div id="maintenance" class="privacy-section">
                <h2>4. Maintenance and Repairs</h2>
                <ul>
                    <li>Tenants shall maintain the property in a clean, rentable condition and use it lawfully.</li>
                    <li>At the end of the stay, the property must be ready for the next guest, requiring only routine cleaning.</li>
                    <li>Costs for damage, repairs, or excessive cleaning will be deducted from the security deposit.</li>
                </ul>
            </div>

            <div id="trash" class="privacy-section">
                <h2>5. Trash</h2>
                <p>All waste must be disposed of in designated trash containers. No debris may be left in or around the property.</p>
            </div>

            <div id="pets" class="privacy-section">
                <h2>6. Pets</h2>
                <ul>
                    <li>Only dogs are permitted in select properties.</li>
                    <li>Guests must notify the host if bringing a dog and pay the associated pet fee.</li>
                    <li>Failure to do so results in forfeiture of the security deposit.</li>
                </ul>
            </div>

            <div id="quiet" class="privacy-section">
                <h2>7. Quiet Enjoyment</h2>
                <ul>
                    <li>Tenants must behave in a respectful, neighborly manner.</li>
                    <li>Quiet hours: 10:00 PM – 8:00 AM. Outdoor noise must be kept minimal.</li>
                    <li>No unregistered guests are permitted on the property during quiet hours.</li>
                </ul>
            </div>

            <div id="smoking" class="privacy-section">
                <h2>8. Smoking</h2>
                <p>Smoking of any kind is strictly prohibited. Violation results in full forfeiture of the security deposit.</p>
            </div>

            <div id="liability" class="privacy-section">
                <h2>9. Liability</h2>
                <ul>
                    <li>Tenants and their guests indemnify and hold the Landlord harmless from claims of injury, damage, or loss.</li>
                    <li>Landlord's insurance does not cover tenants' personal property. Guests should obtain their own insurance if desired.</li>
                </ul>
            </div>

            <div id="deposit" class="privacy-section">
                <h2>10. Rental Deposit</h2>
                <p>The security deposit is refundable if no damages are incurred.</p>
            </div>

            <div id="fees" class="privacy-section">
                <h2>11. Attorney's Fees</h2>
                <p>Tenants agree to pay all reasonable attorney's fees and expenses incurred by the Landlord in enforcing this Agreement.</p>
            </div>

            <div id="use" class="privacy-section">
                <h2>12. Use of Property</h2>
                <p>This rental is for transient occupancy only. Tenants agree not to establish residency.</p>
            </div>

            <div id="stays" class="privacy-section">
                <h2>13. Shortened Stays & Conditions</h2>
                <p>No refunds will be issued for shortened stays, ruined expectations due to weather, acts of God, or circumstances outside the Landlord's control.</p>
            </div>

            <div id="firearms" class="privacy-section">
                <h2>14. Firearms</h2>
                <ul>
                    <li>Only legally owned and permitted firearms are allowed, in compliance with State and local laws.</li>
                    <li>Firearms must be stored properly and may not be discharged on the property.</li>
                </ul>
            </div>

            <div id="fireworks" class="privacy-section">
                <h2>15. Fireworks</h2>
                <p>Fireworks and hazardous materials are prohibited. Violation is grounds for immediate eviction.</p>
            </div>

            <div id="illegal" class="privacy-section">
                <h2>16. Illegal Use</h2>
                <p>Illegal activities, including but not limited to drug use, underage drinking, or harboring fugitives, result in immediate eviction and loss of rent/deposits.</p>
            </div>

            <div id="possessions" class="privacy-section">
                <h2>17. Possessions</h2>
                <ul>
                    <li>Left-behind items will be held for up to 3 months.</li>
                    <li>If unclaimed, items become the property of the Landlord.</li>
                    <li>Landlord is not responsible for the condition of returned items.</li>
                </ul>
            </div>

            <div id="tv" class="privacy-section">
                <h2>18. TV & Streaming</h2>
                <ul>
                    <li>TV and streaming devices are provided.</li>
                    <li>Tenants are responsible for any unauthorized purchases made.</li>
                </ul>
            </div>

            <div id="internet" class="privacy-section">
                <h2>19. Internet</h2>
                <ul>
                    <li>High-speed internet is provided as a courtesy, not a guarantee.</li>
                    <li>No refunds for outages, speed issues, or personal preferences.</li>
                </ul>
            </div>

            <div id="cancellation" class="privacy-section">
                <h2>20. Cancellation Policy</h2>
                <ul>
                    <li>Each property may have unique cancellation terms (see listing).</li>
                    <li>General policy: Reservations made more than 72 hours in advance are eligible for a full refund if canceled within 48 hours of booking.</li>
                    <li>Beyond this period, posted cancellation rules apply.</li>
                    <li>Weather, travel changes, or outside circumstances do not override stated policies.</li>
                    <li>Extenuating circumstances, as defined by the booking platform, may qualify for full refunds.</li>
                </ul>
            </div>

            <div id="law" class="privacy-section">
                <h2>21. Governing Law</h2>
                <p>This Agreement is governed under the laws of Arkansas.</p>
            </div>

            <div id="acknowledgement" class="privacy-section">
                <h2>ACKNOWLEDGEMENT</h2>
                <p>By confirming a reservation with Bentonville Lodging Co LLC, the Tenant acknowledges and agrees to all terms and conditions in this Short-Term Rental Agreement.</p>
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get all navigation links
        const navLinks = document.querySelectorAll('.privacy-nav-link');
        
        // Add click event listeners to smooth scroll to sections
        navLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                
                const targetId = this.getAttribute('href');
                const targetSection = document.querySelector(targetId);
                
                if (targetSection) {
                    // Scroll to the section
                    window.scrollTo({
                        top: targetSection.offsetTop - 80, // Offset to account for any fixed headers
                        behavior: 'smooth'
                    });
                    
                    // Update active state in navigation
                    navLinks.forEach(navLink => navLink.classList.remove('active'));
                    this.classList.add('active');
                }
            });
        });
        
        // Highlight active section on scroll
        const sections = document.querySelectorAll('.privacy-section');
        
        function highlightNavOnScroll() {
            let scrollPosition = window.scrollY + 100;
            
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.clientHeight;
                const sectionId = section.getAttribute('id');
                
                if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight) {
                    navLinks.forEach(link => {
                        link.classList.remove('active');
                        if (link.getAttribute('href') === '#' + sectionId) {
                            link.classList.add('active');
                        }
                    });
                }
            });
        }
        
        // Initialize highlight on page load
        highlightNavOnScroll();
        
        // Update highlight on scroll
        window.addEventListener('scroll', highlightNavOnScroll);
    });
</script>

{!! $data->seo_section !!}
@stop
@section("css")
@parent

@stop

