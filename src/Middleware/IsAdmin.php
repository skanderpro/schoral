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
		if (!\Session::has('right')) {
			return redirect(route('auth'));
		}

		return $next($request);
	}
}
