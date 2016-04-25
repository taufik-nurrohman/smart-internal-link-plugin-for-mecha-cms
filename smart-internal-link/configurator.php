<?php if($logs = glob(__DIR__ . DS . 'log' . DS . 'posts' . DS . '*' . DS . '*.log', GLOB_NOSORT)): ?>
<form class="form-kill" action="<?php echo $config->url_current; ?>/do:kill" method="post">
  <?php echo Form::hidden('token', $token); ?>
  <?php foreach($logs as $log): ?>
  <?php $log = File::open($log)->read(); Notify::warning(preg_replace('#"(' . $config->protocol . '.*?)"#', '<code><a href="$1" target="_blank">$1</a></code>', $log)); ?>
  <?php endforeach; ?>
  <?php echo Notify::read(); ?>
  <p><?php echo Jot::button('destruct', $speak->delete); ?></p>
</form>
<?php else: ?>
<p><?php echo Config::speak('notify_not_available', $speak->config); ?></p>
<?php endif; ?>