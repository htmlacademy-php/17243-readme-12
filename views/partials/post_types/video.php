<div class="post-video__block">
    <div class="post-video__preview">
        <?= embed_youtube_cover(esc($youtube_url), '100%', 'auto') ?? '' ?>
    </div>
    <div class="post-video__control">
        <button class="post-video__play post-video__play--paused button button--video" type="button"><span class="visually-hidden">Запустить видео</span></button>
        <div class="post-video__scale-wrapper">
            <div class="post-video__scale">
                <div class="post-video__bar">
                    <div class="post-video__toggle"></div>
                </div>
            </div>
        </div>
        <button class="post-video__fullscreen post-video__fullscreen--inactive button button--video" type="button"><span class="visually-hidden">Полноэкранный режим</span></button>
    </div>
    <button class="post-video__play-big button" type="button">
        <svg class="post-video__play-big-icon" width="27" height="28">
            <use xlink:href="#icon-video-play-big"></use>
        </svg>
        <span class="visually-hidden">Запустить проигрыватель</span>
    </button>
</div>
