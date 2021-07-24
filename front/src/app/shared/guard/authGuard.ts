import {Injectable} from "@angular/core";
import {
  ActivatedRouteSnapshot,
  CanActivate, CanLoad, Route,
  Router,
  RouterStateSnapshot, UrlSegment,
  UrlTree
} from "@angular/router";
import { Observable } from "rxjs";
import { map, take } from "rxjs/operators";
import { AuthService } from "../service/auth.service";

@Injectable({
  providedIn: 'any'
})
export class AuthGuard implements CanLoad, CanActivate {

  constructor(
    private router: Router,
    private authService: AuthService,
  ) {}

  canActivate(next: ActivatedRouteSnapshot, state: RouterStateSnapshot): Observable<boolean | UrlTree> {
    return this.authService.isLogged().pipe(
      take(1),
      map(can => {
        console.log( can);
        if (!can) {
          this.router.navigate(['connexion']);
        }
        return can;

      })
    );
  }
  canLoad(route: Route, segments: UrlSegment[]): Observable<boolean | UrlTree> {
    return this.authService.isLogged().pipe(
      take(1),
      map(can => {
        console.log( can );
        if(!can) {
          this.router.navigate(['connexion']);
        }
        return can;
      })
    );
  }
}
