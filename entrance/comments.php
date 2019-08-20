<?php if (comments_open() || have_comments()) : ?>

	<div id="comments">

		<?php if (post_password_required()) : ?>
			<p class="nopassword"><?php _e('This post is password protected. Enter the password to view any comments.', 'g7theme'); ?></p>
		<?php else : ?>

			<?php if (have_comments()) : ?>
				<h2 id="comments-title">
					<?php
						printf(
							_n(
								'%1$s comment',
								'%1$s comments',
								get_comments_number(),
								'g7theme'
							),
							number_format_i18n(get_comments_number())
						);
					?>
				</h2>

				<?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : // are there comments to navigate through ?>
				<nav id="comment-nav-above" class="clearfix">
					<h1 class="assistive-text"><?php _e('Comment navigation', 'g7theme'); ?></h1>
					<div class="nav-previous"><?php previous_comments_link(__('&larr; Older Comments', 'g7theme')); ?></div>
					<div class="nav-next"><?php next_comments_link(__('Newer Comments &rarr;', 'g7theme')); ?></div>
				</nav>
				<?php endif; // check for comment navigation ?>

				<ul class="commentlist">
					<?php wp_list_comments(array('callback' => 'g7_commentlist')); ?>
				</ul>

				<?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : // are there comments to navigate through ?>
				<nav id="comment-nav-below" class="clearfix">
					<h1 class="assistive-text"><?php _e('Comment navigation', 'g7theme'); ?></h1>
					<div class="nav-previous"><?php previous_comments_link(__('&larr; Older Comments', 'g7theme')); ?></div>
					<div class="nav-next"><?php next_comments_link(__('Newer Comments &rarr;', 'g7theme')); ?></div>
				</nav>
				<?php endif; // check for comment navigation ?>
			<?php endif; ?>

			<?php if (!comments_open() && post_type_supports(get_post_type(), 'comments')) : ?>
				<p class="nocomments"><?php _e('Comments are closed.', 'g7theme'); ?></p>
			<?php endif; ?>

			<?php comment_form(); ?>

		<?php endif; ?>

	</div>

<?php endif; ?>