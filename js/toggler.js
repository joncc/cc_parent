
// Code from JΛ̊KE on http://forum.jquery.com/topic/beginner-function-toggle-deprecated-what-to-use-instead

(function($){
	$.fn.toggler=function(){
		var functions=arguments, iteration=0
		return this.click(function(){
			functions[iteration].apply(this,arguments)
			iteration= (iteration+1) %functions.length
		})
	}
}(jQuery))