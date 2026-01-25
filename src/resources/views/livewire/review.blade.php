<div>
    <div>
        <button wire:click="completeTransaction">
            取引を完了する
        </button>
        @if ($showModal)
            <div class="review">
                <h1>取引が完了しました</h1>
                <p>今回の取引相手はどうでしたか?
                </p>
                <div class="star">
                    @for ($i = 1; $i <= 5; $i++)
                        <span wire:click="setRating({{ $i }})" wire:click="setRating({{ $i }})"
                            class="star @if ($i <= $rating) selected @endif">⭐︎</span>
                    @endfor
                </div>
                <button wire:click="submitReview" type="button">送信する</button>
            </div>
        @endif
    </div>
</div>
