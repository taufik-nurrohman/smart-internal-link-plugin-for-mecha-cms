<form class="form-kill" action="<?php echo $config->url_current; ?>/kill" method="post">
  <?php echo Form::hidden('token', $token); ?>
  <?php $logs = glob(PLUGIN . DS . File::B(__DIR__) . DS . 'log' . DS . '*' . DS . '*.log', GLOB_NOSORT); if($logs): ?>
  <?php foreach($logs as $log): ?>
  <?php $log = explode('=>', File::open($log)->read(), 2); Notify::warning(preg_replace('#"(' . $config->protocol . '.*?)"#', '<code><a href="$1" target="_blank">$1</a></code>', trim($log[0]))); ?>
  <?php endforeach; ?>
  <?php echo Notify::read(); ?>
  <p><?php echo Jot::button('destruct', $speak->delete); ?></p>
  <?php endif; ?>
</form>