<div class="nav-search">
	
	<ul class="nav">
		<?php foreach (Pages::getPagesList('5') as $page) : ?>
		<li class="<?php if (URL::getPath() == $page['path']): ?>active<?php endif; ?>">
			<a href="/<?php echo $page['path']; ?>/"><?php echo $page['name']; ?></a>
		</li>
		<?php endforeach; ?>
	</ul>
	
	<div class="search">
		
		<form id="search-form" action="/category/search/" method="get">
			
			<div>
				
				<div class="field">
					
					<input id="search-fld" type="text" name="q" autocomplete="off"  value="<?php if (!@$_GET['q']) echo 'Поиск...'; else echo $_GET['q']; ?>">
					<i class="cl"></i>
					<i class="cr"></i>
				
				</div>
				
				<div class="field code">
					
					<input id="search-fld-code" type="text" name="code" autocomplete="off"  value="Поиск по коду">
					<i class="cl"></i>
					<i class="cr"></i>
								
				</div>
				
				<input type="submit" value="Найти" class="btn-search">
				
				<div id="pred_result"></div>
			</div>
			<ul>
				<li>
					<?php if((@$_SESSION['user']) and (@$_SESSION['user']['active']==1)): ?>
					<a class="btn-link" id="btn-logout" href="/registration/logout/">Выход</a>
					<a class="btn-link"  id="btn-cabinet" href="/cabinet/">Личный кабинет</a>
					<?php else: ?>
					<div id="btns-account">
						<span  class="btn-link" id="show-login">Вход</span>
						<a  class="btn-link" href="/registration">Регистрация</a>
					</div>					
					<?php endif; ?>
					<a id="btn-logout" href="/registration/logout/" class="btn-link hidden">Выход</a>
					<a id="btn-cabinet" href="/cabinet/" class="btn-link hidden">Личный кабинет</a>					
				</li>
				<li style="display:none;">
					<div class="w-radio medium"><input id="search-in-catalog" type="radio" name="w" value="catalog" checked="checked"></div><label for="search-in-catalog">по каталогу</label>
					<div class="w-radio medium"><input id="search-in-site" type="radio" name="w" value="site"></div><label for="search-in-site">по сайту</label>
				</li>
			</ul>
		</form>
		
		<form id="login-form" action="/registration/login" method="post">
			
			<ul>
				<li><label for="login">Логин</label></li>
				<li>
					<div class="field">
						<input id="login" type="text" name="login" maxlength="32">
						<i class="cl"></i>
						<i class="cr"></i>
					</div>
				</li>
				<li>
					<span class="btn-link" id="back-to-search">К поиску</span>
					<a class="btn-link" href="/registration">Регистрация</a>
				</li>
			</ul>
			<ul>
				<li><label for="pass">Пароль</label></li>
				<li>
					<div class="field">
						<input id="pass" type="password" name="pass" maxlength="20">
						<i class="cl"></i>
						<i class="cr"></i>
					</div>
				</li>
				<li><div class="w-checkbox medium"><input id="remember" type="checkbox" name="remember" value="1"></div><label for="remember">запомнить меня</label></li>
			</ul>
			<input type="hidden" name="r" value="Lw==">
			<input type="submit" value="Войти" class="in-enter">
		</form>
	</div>
</div>				