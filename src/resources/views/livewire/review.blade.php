<div>
    <div>
        <button wire:click="openModal" type="button"
            class="px-4 py-2 font-bold text-white bg-blue-500 rounded hover:bg-blue-700">
            モーダルを表示
        </button>

        @if ($showModal)
            <div class="review">
                <h1>取引が完了しました</h1>
                <p>今回の取引相手はどうでしたか?
                </p>
                <p>⭐️⭐️⭐️⭐️⭐️</p>
                <button type="submit">送信する</button>
            </div>
        @endif
    </div>
</div>
