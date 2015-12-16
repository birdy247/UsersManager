<?= $this->Form->create() ?>
<div class="form-group has-feedback">
    <?= $this->Form->input('email', ['label' => 'Email', 'label' => false]) ?>
    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
</div>
<div class="form-group has-feedback">
    <?= $this->Form->input('password', ['type' => 'password', 'label' => false]) ?>
    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
</div>
<div class="row">
    <div class="col-xs-8">    
        <div class="checkbox icheck">
            <label>
                <?= $this->Form->checkbox('remember_me', ['type' => 'chexbox', 'label' => false]) ?>
                Remember Me
            </label>
        </div>                        
    </div><!-- /.col -->
    <div class="col-xs-4">
        <?= $this->Form->button('Login', ['class' => 'btn btn-primary btn-block btn-flat']); ?>
    </div><!-- /.col -->
</div>
<?= $this->Form->end() ?>