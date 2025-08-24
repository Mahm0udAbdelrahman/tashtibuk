<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class DashboardNotification extends Notification
{
    use Queueable;

    protected $type_id;
    protected $name;
    protected $price;
    protected $type;

    /**
     * Create a new notification instance.
     */
    public function __construct($type_id, $name, $price, $type)
    {
        $this->type_id = $type_id;
        $this->name    = $name;
        $this->price   = $price;
        $this->type    = $type;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        if ($this->type === 'car') {
            return [
                'id'      => $this->type_id,
                'name'    => $this->name,
                'price'   => $this->price,
                'message' => 'تم إضافة سيارة جديدة: ' . $this->name . ' بسعر ' . $this->price . ' $',
                'url'     => route('Admin.cars.edit', ['car' => $this->type_id]),
            ];
        } elseif ($this->type === 'withdraw') {
            return [
                'id'      => $this->type_id,
                'price'   => $this->price,
                'message' => 'لديك طلب سحب جديد بقيمة ' . $this->price . ' $',
                'url'     => route('Admin.withdraw_money.edit', ['withdraw_money' => $this->type_id]),
            ];
        }elseif($this->type === 'auction')
        {
            return [
                'id'      => $this->type_id,
                'name'    => $this->name,
                'price'   => $this->price,
                'message' => 'تم الفوز بمزاد على السيارة: ' . $this->name . ' بسعر ' . $this->price . ' $. يرجى متابعة الإجراءات.',
                'url'     => route('Admin.auctions.show', ['id' => $this->type_id]),
            ];
        }

        return [];
    }
}
