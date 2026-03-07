<?php

namespace Tests\Unit\Integrations\Guesty;

use App\Integrations\Guesty\Contracts\GuestyClientInterface;
use App\Integrations\Guesty\GuestyBookingApi;
use App\Integrations\Guesty\GuestyGuestApi;
use App\Integrations\Guesty\GuestyPropertyApi;
use App\Integrations\Guesty\GuestyQuoteApi;
use App\Integrations\Guesty\GuestyReviewApi;
use App\Models\GuestyAvailabilityPrice;
use App\Models\GuestyProperty;
use App\Models\GuestyPropertyBooking;
use App\Models\GuestyPropertyReview;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuestyApiTest extends TestCase
{
    use RefreshDatabase;

    private GuestyClientInterface $mockClient;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockClient = $this->createMock(GuestyClientInterface::class);
    }

    /* ------------------------------------------------------------------ */
    /*  GuestyPropertyApi                                                  */
    /* ------------------------------------------------------------------ */

    public function test_sync_properties_upserts_records(): void
    {
        $this->mockClient->method('openApiGet')
            ->willReturnOnConsecutiveCalls(
                [
                    'status' => 200,
                    'data'   => [
                        'results' => [
                            ['_id' => 'prop-1', 'title' => 'Villa A', 'active' => true, 'accommodates' => 4],
                            ['_id' => 'prop-2', 'title' => 'Villa B', 'active' => false, 'accommodates' => 6],
                        ],
                        'count' => 2,
                    ],
                ],
                [
                    'status' => 200,
                    'data'   => [
                        'results' => [],
                        'count'   => 0,
                    ],
                ]
            );

        $api = new GuestyPropertyApi($this->mockClient);
        $result = $api->syncProperties();

        $this->assertEquals(200, $result['status']);
        $this->assertEquals(2, $result['count']);
        $this->assertDatabaseHas('guesty_properties', ['_id' => 'prop-1', 'title' => 'Villa A']);
        $this->assertDatabaseHas('guesty_properties', ['_id' => 'prop-2', 'title' => 'Villa B']);
    }

    public function test_sync_availability_replaces_existing_data(): void
    {
        // Pre-existing data for the same listing that should be removed
        GuestyAvailabilityPrice::create([
            'listingId'  => 'listing-xyz',
            'start_date' => '2025-01-01',
            'price'      => 100,
            'minNights'  => 1,
        ]);

        $this->mockClient->method('openApiGet')
            ->willReturn([
                'status' => 200,
                'data'   => [
                    'data' => [
                        'days' => [
                            ['date' => '2025-06-01', 'price' => 200, 'minNights' => 2, 'status' => 'available'],
                            ['date' => '2025-06-02', 'price' => 250, 'minNights' => 2, 'status' => 'available'],
                        ],
                    ],
                ],
            ]);

        $api = new GuestyPropertyApi($this->mockClient);
        $result = $api->syncAvailability('listing-xyz');

        $this->assertEquals(200, $result['status']);
        $this->assertEquals(2, $result['days']);
        // Old data for this listing should be replaced
        $this->assertDatabaseMissing('guesty_availablity_prices', [
            'listingId' => 'listing-xyz',
            'price'     => 100,
        ]);
        $this->assertDatabaseHas('guesty_availablity_prices', [
            'listingId' => 'listing-xyz',
            'price'     => 200,
        ]);
    }

    /* ------------------------------------------------------------------ */
    /*  GuestyBookingApi                                                   */
    /* ------------------------------------------------------------------ */

    public function test_sync_bookings_truncates_and_reimports(): void
    {
        // Pre-existing data
        GuestyPropertyBooking::create([
            '_id'       => 'old-booking',
            'listingId' => 'old-listing',
        ]);

        $this->mockClient->method('openApiGet')
            ->willReturnOnConsecutiveCalls(
                [
                    'status' => 200,
                    'data'   => [
                        'results' => [
                            [
                                '_id'              => 'booking-1',
                                'confirmationCode' => 'CONF-001',
                                'checkIn'          => '2025-06-01T16:00:00.000Z',
                                'checkOut'         => '2025-06-05T10:00:00.000Z',
                                'listingId'        => 'listing-abc',
                                'accountId'        => 'acc-1',
                                'guestId'          => 'guest-1',
                            ],
                        ],
                    ],
                ],
                [
                    'status' => 200,
                    'data'   => ['results' => []],
                ]
            );

        $api = new GuestyBookingApi($this->mockClient);
        $result = $api->syncBookings();

        $this->assertEquals(200, $result['status']);
        $this->assertEquals(1, $result['count']);
        $this->assertDatabaseMissing('guesty_property_bookings', ['_id' => 'old-booking']);
        $this->assertDatabaseHas('guesty_property_bookings', [
            '_id'              => 'booking-1',
            'confirmationCode' => 'CONF-001',
        ]);
    }

    public function test_create_reservation_posts_data(): void
    {
        $this->mockClient->method('openApiPost')
            ->willReturn(['status' => 200, 'data' => ['_id' => 'new-res']]);

        $api = new GuestyBookingApi($this->mockClient);
        $result = $api->createReservation(['listingId' => 'listing-1']);

        $this->assertEquals(200, $result['status']);
    }

    public function test_confirm_reservation_puts_status(): void
    {
        $this->mockClient->method('openApiPut')
            ->willReturn(['status' => 200, 'data' => ['status' => 'confirmed']]);

        $api = new GuestyBookingApi($this->mockClient);
        $result = $api->confirmReservation('res-123');

        $this->assertEquals(200, $result['status']);
    }

    /* ------------------------------------------------------------------ */
    /*  GuestyGuestApi                                                     */
    /* ------------------------------------------------------------------ */

    public function test_create_guest_posts_data(): void
    {
        $this->mockClient->method('openApiPost')
            ->willReturn(['status' => 200, 'data' => ['_id' => 'guest-abc']]);

        $api = new GuestyGuestApi($this->mockClient);
        $result = $api->createGuest('John', 'Doe', 'john@example.com', '555-1234');

        $this->assertEquals(200, $result['status']);
    }

    public function test_get_guest_fetches_data(): void
    {
        $this->mockClient->method('openApiGet')
            ->willReturn(['status' => 200, 'data' => ['fullName' => 'John Doe']]);

        $api = new GuestyGuestApi($this->mockClient);
        $result = $api->getGuest('guest-abc');

        $this->assertEquals(200, $result['status']);
        $this->assertEquals('John Doe', $result['data']['fullName']);
    }

    /* ------------------------------------------------------------------ */
    /*  GuestyQuoteApi                                                     */
    /* ------------------------------------------------------------------ */

    public function test_create_detailed_quote_sends_guest_breakdown(): void
    {
        $this->mockClient->expects($this->once())
            ->method('openApiPost')
            ->with(
                'quotes',
                $this->callback(function (array $data) {
                    return isset($data['numberOfGuests']['numberOfAdults'])
                        && $data['numberOfGuests']['numberOfAdults'] === 2
                        && $data['numberOfGuests']['numberOfChildren'] === 1;
                })
            )
            ->willReturn(['status' => 200, 'data' => ['_id' => 'quote-1']]);

        $api = new GuestyQuoteApi($this->mockClient);
        $result = $api->createDetailedQuote(3, 2, 1, '2025-06-01', '2025-06-05', 'listing-1');

        $this->assertEquals(200, $result['status']);
    }

    public function test_create_simple_quote_sends_guest_count(): void
    {
        $this->mockClient->expects($this->once())
            ->method('openApiPost')
            ->with(
                'quotes',
                $this->callback(fn (array $data) => $data['numberOfGuests'] === 3)
            )
            ->willReturn(['status' => 200, 'data' => ['_id' => 'quote-2']]);

        $api = new GuestyQuoteApi($this->mockClient);
        $result = $api->createSimpleQuote(3, '2025-06-01', '2025-06-05', 'listing-1');

        $this->assertEquals(200, $result['status']);
    }

    public function test_create_booking_engine_quote_uses_booking_api(): void
    {
        $this->mockClient->expects($this->once())
            ->method('bookingApiPost')
            ->willReturn(['status' => 200, 'data' => ['_id' => 'bq-1']]);

        $api = new GuestyQuoteApi($this->mockClient);
        $result = $api->createBookingEngineQuote(4, '2025-06-01', '2025-06-05', 'listing-1', 'SUMMER10');

        $this->assertEquals(200, $result['status']);
    }

    /* ------------------------------------------------------------------ */
    /*  GuestyReviewApi                                                    */
    /* ------------------------------------------------------------------ */

    public function test_sync_reviews_truncates_and_reimports(): void
    {
        GuestyPropertyReview::create(['_id' => 'old-review', 'listingId' => 'old-listing']);

        $guestApi = $this->createMock(GuestyGuestApi::class);
        $guestApi->method('getGuest')
            ->willReturn(['status' => 200, 'data' => ['fullName' => 'Jane Smith']]);

        $this->mockClient->method('openApiGet')
            ->willReturnOnConsecutiveCalls(
                [
                    'status' => 200,
                    'data'   => [
                        'data' => [
                            [
                                '_id'        => 'rev-1',
                                'listingId'  => 'listing-1',
                                'guestId'    => 'guest-1',
                                'channelId'  => 'airbnb',
                            ],
                        ],
                    ],
                ],
                [
                    'status' => 200,
                    'data'   => ['data' => []],
                ]
            );

        $api = new GuestyReviewApi($this->mockClient, $guestApi);
        $result = $api->syncReviews();

        $this->assertEquals(200, $result['status']);
        $this->assertEquals(1, $result['count']);
        $this->assertDatabaseMissing('guesty_property_reviews', ['_id' => 'old-review']);
        $this->assertDatabaseHas('guesty_property_reviews', [
            '_id'       => 'rev-1',
            'listingId' => 'listing-1',
            'full_name' => 'Jane Smith',
        ]);
    }
}
