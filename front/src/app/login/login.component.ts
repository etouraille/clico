import { Component, OnInit } from '@angular/core';
import {HttpClient} from "@angular/common/http";
import {AuthService} from "@shared";

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css']
})
export class LoginComponent implements OnInit {

  email: string = '';
  name: string = '';
  password: string = '';

  isSubscribe: boolean = false;
  isPassword: boolean = false;

  constructor(private http: HttpClient, private authService: AuthService) { }

  ngOnInit(): void {
  }

  checkEmail(): void {
    console.log(this.email);
    this.http.get('/emailExists/' + this.email).subscribe((data: any) => {
      if (!!!data.exists) {
        this.isSubscribe = true;
      } else {
        this.isPassword = true;
      }
    })
  }

  displayPassword(): void {
    console.log('is password');
    this.isPassword = true;
  }

  submit(): void {
    if (this.isSubscribe) {
      this.http.post('/subscribe', {
        email: this.email,
        password : this.password,
        name : this.name
      }).subscribe((jwt: any) => {
        this.authService.authenticate(jwt);
      })
    } else {
      this.http.post('/login', {
        username: this.email,
        password : this.password,
      }).subscribe((jwt: any) => {
        this.authService.authenticate(jwt);
      })
    }
  }

  ping(): void {
    this.http.get('/api/ping').subscribe(data => {
      console.log(data);
    })
  }
}
