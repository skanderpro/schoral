<?php

namespace Qubants\Scholar\Middleware;

use Closure;

class IsAdmin
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  \Closure $next
	 * @param  string|null $guard
	 * @return mixed
	 */
	public function handle($request, Closure $next, $guard = null) {
		if(\Cookie::get('scholar_a',false)===false){
			abort(404);
		}
		if (!\Session::has('right')) {
			return redirect(route('auth'));
		}

		return $next($request);
	}
}
