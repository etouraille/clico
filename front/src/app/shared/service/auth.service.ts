import { Injectable } from '@angular/core';
import {HttpClient} from "@angular/common/http";
import {Observable, of} from "rxjs";

@Injectable({
  providedIn: 'root'
})
export class AuthService {

  constructor(private http: HttpClient) {}

  getUserToken(): string {
    const jwtString = window.localStorage.getItem('jwt');
    let logged;
    if (jwtString) {
      const jwt: { expire: number, token : string } = JSON.parse(jwtString);
      return jwt.token;
    } else {
      return null;
    }
  }

  isLogged(): Observable<boolean|null> {
    const jwtString = window.localStorage.getItem('jwt');
    let logged;
    if(jwtString) {
      const jwt: { expire: number, token : string } = JSON.parse(jwtString);
      if (jwt.expire > (Math.floor(Date.now() / 1000))){
        logged = true;
      } else {
        logged = false;
      }
    }
    return of(logged);
  }

  authenticate(jwt: any ): void {
    if (jwt.expire && jwt.token) {
      window.localStorage.setItem('jwt', JSON.stringify(jwt));
    } else {
      throw new Error('Wrong format');
    }
  }
}
