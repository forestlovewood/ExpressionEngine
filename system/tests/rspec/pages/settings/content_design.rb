class ContentDesign < ControlPanelPage

	element :new_posts_clear_caches_y, 'input[name=new_posts_clear_caches][value=y]'
	element :new_posts_clear_caches_n, 'input[name=new_posts_clear_caches][value=n]'
	element :enable_sql_caching_y, 'input[name=enable_sql_caching][value=y]'
	element :enable_sql_caching_n, 'input[name=enable_sql_caching][value=n]'
	element :auto_assign_cat_parents_y, 'input[name=auto_assign_cat_parents][value=y]'
	element :auto_assign_cat_parents_n, 'input[name=auto_assign_cat_parents][value=n]'

	def load
		settings_btn.click
		click_link 'Content & Design'
	end
end