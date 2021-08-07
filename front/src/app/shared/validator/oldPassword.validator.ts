import { AbstractControl, AsyncValidatorFn, ValidationErrors } from '@angular/forms';
import { Observable } from 'rxjs';
import { map } from 'rxjs/operators';
import { HttpClient } from "@angular/common/http";

export class OldPasswordValidator {
  static createValidator(http: HttpClient): AsyncValidatorFn {
    return (control: AbstractControl): Observable<ValidationErrors> => {
      return http.post('/api/is-password-valid', {password: control.value}).pipe(
        map((result: any) => result.valid ? null : {invalidAsync: true})
      );
    };
  }
}
