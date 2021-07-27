import { Component, OnInit } from '@angular/core';
import {HttpClient} from "@angular/common/http";
import {AuthService} from "@shared";
import {FormBuilder, Validators} from "@angular/forms";
import { faAt, faEye } from '@fortawesome/free-solid-svg-icons';
import {Route, Router} from "@angular/router";

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

  passwordType: string = 'password';


  loginForm = this.fb.group({
    email: ['', Validators.email],
    name: [''],
    password: ['']
  })

  constructor(
    private http: HttpClient,
    private authService: AuthService,
    private fb: FormBuilder,
    private router: Router,
  ) { }

  ngOnInit(): void {

  }


  checkEmail(): void {
    this.http.get('/emailExists/' + this.loginForm.get('email').value).subscribe((data: any) => {
      if (!!!data.exists) {
        this.isSubscribe = true;
        this.isPassword = false;
        setTimeout(() => {
          document.getElementById("name").focus()
        })

      } else {
        this.isPassword = true;
        this.isSubscribe = false;
        setTimeout(() => {
          document.getElementById("password").focus()
        })

      }
    })
  }

  displayPassword(): void {
    this.isPassword = true;
    setTimeout(() => {
      document.getElementById("password").focus()
    })
  }

  toggleDisplayPassword(): void {
    this.passwordType = this.passwordType === 'text' ? 'password' : 'text';
  }

  submit(): void {
    if (this.isSubscribe) {
      this.http.post('/subscribe', {
        email: this.loginForm.value.email,
        password : this.loginForm.value.password,
        name : this.loginForm.value.name,
      }).subscribe((jwt: any) => {
        this.authService.authenticate(jwt);
        this.router.navigate(['je-vends']);
      })
    } else {
      this.http.post('/login', {
        username: this.loginForm.value.email,
        password : this.loginForm.value.password,
      }).subscribe((jwt: any) => {
        this.authService.authenticate(jwt);
        this.router.navigate(['je-vends']);
      })
    }
  }

  isDisabled(): boolean {
    return !this.loginForm.valid ||
      !this.isPassword;
  }
}
