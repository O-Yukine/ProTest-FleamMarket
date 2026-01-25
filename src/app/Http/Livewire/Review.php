<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Review as ReviewModel;


class Review extends Component
{

    public $showModal = false;
    public $rating = 0;
    public $chat;

    public function mount($chat)
    {
        $this->chat = $chat;

        // 出品者：取引完了後に自動で開く
        if (
            auth()->id() === $chat->seller_id &&
            $chat->status === 'completed' &&
            $this->canReview()
        ) {
            $this->showModal = true;
        }
    }


    public function render()

    {
        return view('livewire.review');
    }

    public function completeTransaction()
    {

        $this->chat->update(['status' => 'completed']);
        $this->showModal = true;
    }

    public function canReview()
    {
        return !ReviewModel::where('chat_id', $this->chat->id)
            ->where('reviewer_id', auth()->id())
            ->exists();
    }

    public function setRating($value)
    {
        $this->rating = $value;
    }

    public function submitReview()
    {
        ReviewModel::create([
            'chat_id' => $this->chat->id,
            'reviewer_id' => auth()->id(),
            'reviewee_id' =>
            auth()->id() === $this->chat->buyer_id
                ? $this->chat->seller_id
                : $this->chat->buyer_id,
            'score' => $this->rating,
        ]);

        session()->flash('message', 'レビューを送信しました');
    }
}
