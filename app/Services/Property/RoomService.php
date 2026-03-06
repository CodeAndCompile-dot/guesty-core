<?php

namespace App\Services\Property;

use App\Models\PropertyRoom;
use App\Models\PropertyRoomItem;
use App\Models\PropertyRoomItemImage;
use App\Services\Media\UploadService;
use App\Support\Traits\HasImageUpload;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class RoomService
{
    use HasImageUpload;

    public function __construct(
        protected UploadService $uploadService,
    ) {
    }

    /* ------------------------------------------------------------------ */
    /*  Property Rooms (Groups)                                             */
    /* ------------------------------------------------------------------ */

    public function getRoomsByProperty(int|string $propertyId): Collection
    {
        return PropertyRoom::where('property_id', $propertyId)->orderBy('id', 'desc')->get();
    }

    public function findRoom(int|string $id): ?PropertyRoom
    {
        return PropertyRoom::find($id);
    }

    public function findRoomOrFail(int|string $id): PropertyRoom
    {
        return PropertyRoom::findOrFail($id);
    }

    public function storeRoom(int|string $propertyId, array $data): PropertyRoom
    {
        $data['property_id'] = $propertyId;

        return PropertyRoom::create($data);
    }

    public function updateRoom(int|string $id, array $data): bool
    {
        return PropertyRoom::findOrFail($id)->update($data);
    }

    public function destroyRoom(int|string $id): bool
    {
        // Cascade: delete items and their images
        $room = PropertyRoom::findOrFail($id);
        $items = PropertyRoomItem::where('room_id', $id)->get();
        foreach ($items as $item) {
            PropertyRoomItemImage::where('sub_room_id', $item->id)->delete();
        }
        PropertyRoomItem::where('room_id', $id)->delete();

        return $room->delete();
    }

    public function activateRoom(int|string $id): bool
    {
        return PropertyRoom::findOrFail($id)->update(['room_status' => 'active']);
    }

    public function deactivateRoom(int|string $id): bool
    {
        return PropertyRoom::findOrFail($id)->update(['room_status' => 'inactive']);
    }

    /* ------------------------------------------------------------------ */
    /*  Room Items (Sub-Rooms)                                              */
    /* ------------------------------------------------------------------ */

    public function getItemsByRoom(int|string $roomId): Collection
    {
        return PropertyRoomItem::where('room_id', $roomId)->orderBy('id', 'desc')->get();
    }

    public function findItem(int|string $id): ?PropertyRoomItem
    {
        return PropertyRoomItem::find($id);
    }

    public function findItemOrFail(int|string $id): PropertyRoomItem
    {
        return PropertyRoomItem::findOrFail($id);
    }

    public function storeItem(int|string $roomId, array $data): PropertyRoomItem
    {
        $data['room_id'] = $roomId;

        return PropertyRoomItem::create($data);
    }

    public function updateItem(int|string $id, array $data): bool
    {
        return PropertyRoomItem::findOrFail($id)->update($data);
    }

    public function destroyItem(int|string $id): bool
    {
        PropertyRoomItemImage::where('sub_room_id', $id)->delete();

        return PropertyRoomItem::findOrFail($id)->delete();
    }

    public function activateItem(int|string $id): bool
    {
        return PropertyRoomItem::findOrFail($id)->update(['sub_room_status' => 'active']);
    }

    public function deactivateItem(int|string $id): bool
    {
        return PropertyRoomItem::findOrFail($id)->update(['sub_room_status' => 'inactive']);
    }
}
