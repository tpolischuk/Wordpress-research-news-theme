			</main>

		</div>

		<div id="footer-area">
			<?php if (get_theme_mod('footer_widget', 1)) : ?>
			<div id="footer-widget">
				<div class="container">
					<div class="row">
						<div class="col-md-4">
							<?php dynamic_sidebar('footer1'); ?>
						</div>
						<div class="col-md-4">
							<?php dynamic_sidebar('footer2'); ?>
						</div>
						<div class="col-md-4">
							<?php dynamic_sidebar('footer3'); ?>
						</div>
					</div>
				</div>
			</div>
			<?php endif; ?>

			<footer id="bottom">
				<div class="container">
					<div class="row">
						<div class="col-sm-6 footer1"><?php echo get_theme_mod('footer_text_1'); ?></div>
						<div class="col-sm-6 footer2"><?php echo get_theme_mod('footer_text_2'); ?></div>
					</div>
				</div>
			</footer>
		</div>

	</div>

<?php wp_footer(); ?>
</body>
</html>
