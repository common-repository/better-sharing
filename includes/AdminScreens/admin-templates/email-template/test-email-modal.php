<div id="bswp-email-modal" class="bswp-email-modal bswp-email-modal-hidden">
	<div class="bswp-block-module">
    <span>
      <a href="#" id="bswp-close-test-email-modal">
        <svg width="24px" height="24px" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
          <rect width="48" height="48" fill="white" fill-opacity="0.01"></rect>
          <path d="M14 14L34 34" stroke="black" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"></path>
          <path d="M14 34L34 14" stroke="black" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"></path>
        </svg> 
      </a>
    </span>
		<form accept-charset="UTF-8" method="post">
			<div class="items-center">
				<div class="items-center"> 
            <label for="bswp-share-email-input">to: 
              <input 
                id="bswp-share-email-input"
                type="text"  
                name="bswp-share-email-input"
                placeholder='test@email.com'
                class="form-control border rounded-r-none bswp-share-email-input"
              >
              <p id='coreblock-email-sent-msg'></p>
            </label> 
          <button type="submit" class="button">
            <?php _e($btn_labels['buttons']['send_email'], 'better-sharing-wp'); ?> 
          </button>
				</div>
			</div>
			
		</form>		
	</div>
</div>