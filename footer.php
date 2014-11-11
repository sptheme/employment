    <footer id="footer" role="contentinfo">
        <div class="container clearfix">
        	<nav id="footer-nav" class="clearfix">
	        	<?php echo sp_footer_navigation(); ?>
        	</nav>
            <p class="copyright">
                <?php if ( ot_get_option( 'copyright' ) ): ?>
                    <?php echo ot_get_option( 'copyright' ); ?>
                <?php else: ?>
                    <?php bloginfo(); ?> &copy; <?php echo date( 'Y' ); ?>. <?php _e( 'All Rights Reserved.', SP_TEXT_DOMAIN ); ?>
                <?php endif; ?>
            </p><!--/.copyright-->
            
            <?php if ( ot_get_option( 'credit' ) != 'off' ): ?>
            <p class="credit"><?php echo ot_get_option( 'credit-text' ); ?></p><!--/#credit-->
            <?php endif; ?><!--/#credit-->
            
        </div><!-- .container .clearfix -->
    </footer><!-- #footer -->
    </div> <!-- .inner-content-container -->
    <p class="container licensed">Registered Details: KCTC is a duly authorized and licensed recruitment agency in the Cambodia. License No.Co.1098E in April 2014</p>
    </div> <!-- #content-container -->
</div> <!-- #wrapper -->
    
    

<?php wp_footer(); ?>

</body>
</html>