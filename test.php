<div class="container">
	<div class="row">
		<div class="col">
			<h2>Please Sign Up</h2>
			<p>Already a member? <a href='login.php'>Login</a></p>
			<p class="bg-danger">test</p>
			<hr>
<?php
				session_start();
				require 'vendor/autoload.php';

				if (isset($error)){
					foreach($error as $error){
						echo '<p class="bg-danger">'.$error.'</p>';		
					}
				}
				$msg = new \Plasticbrain\FlashMessages\FlashMessages();
				$msg->info('This is an info message');
				$msg->success('This is a success message');
				$msg->warning('This is a warning message');
				$msg->error('This is an error message 1');
				$msg->error('This is an error message 2');

				if ($msg->hasErrors()){
					$msg->display();
				}
				
				
			?>
	</div>
</div>
