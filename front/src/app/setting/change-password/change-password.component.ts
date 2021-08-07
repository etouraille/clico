import { Component, OnInit } from '@angular/core';
import {AbstractControl, FormBuilder, ValidationErrors, ValidatorFn, Validators} from "@angular/forms";
import {OldPasswordValidator} from "@shared";
import {HttpClient} from "@angular/common/http";

@Component({
  selector: 'app-change-password',
  templateUrl: './change-password.component.html',
  styleUrls: ['./change-password.component.css']
})
export class ChangePasswordComponent implements OnInit {

  checkPasswords: ValidatorFn = (group: AbstractControl):  ValidationErrors | null => {
    let pass = group.get('newPassword').value;
    let confirmPass = group.get('newPasswordConfirm').value
    return pass === confirmPass ? null : { notSame: true }
  }

  changePasswordForm = this.fb.group({
    oldPassword : ['', Validators.required, OldPasswordValidator.createValidator(this.http)],
    newPassword: ['', Validators.required],
    newPasswordConfirm : ['', Validators.required]
  }, {validators: this.checkPasswords})


  constructor(
    private fb: FormBuilder,
    private http: HttpClient,
  ) { }

  ngOnInit(): void {
  }

  disabled(): boolean {
    return !this.changePasswordForm.valid;
  }

  onSubmit(): void {
    this.http.post('/api/change-password', {
      oldPassword: this.changePasswordForm.value.oldPassword,
      newPassword: this.changePasswordForm.value.newPassword,
    }).subscribe(() => { console.log('password changed')});
  }
}
