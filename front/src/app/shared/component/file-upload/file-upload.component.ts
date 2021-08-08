import {Component, forwardRef, Input, OnInit, Output} from '@angular/core';
import { finalize } from "rxjs/operators";
import { HttpClient, HttpEventType } from "@angular/common/http";
import { EventEmitter } from "@angular/core";
import { Subscription } from "rxjs";
import {ControlValueAccessor, NG_VALUE_ACCESSOR} from "@angular/forms";

@Component({
  selector: 'app-file-upload',
  templateUrl: "file-upload.component.html",
  styleUrls: ["file-upload.component.scss"],
  providers: [
    {
      provide: NG_VALUE_ACCESSOR,
      useExisting: forwardRef(() => FileUploadComponent),
      multi: true
    }
  ]
})
export class FileUploadComponent implements ControlValueAccessor{

  @Input()
  requiredFileType:string;

  @Output() onUploadComplete = new EventEmitter<any>();

  icon = '';
  fileName = '';
  uploadProgress:number;
  uploadSub: Subscription;

  propagateChange = (_: any) => {};

  constructor(private http: HttpClient) {}

  writeValue(value: any) {
    if(value) {
      this.icon = value.file;
    }
  }
  registerOnChange(fn: any) {
    this.propagateChange = fn;
  }

  registerOnTouched(fn: any) {
  }

  onFileSelected(event) {
    const file:File = event.target.files[0];

    if (file) {
      this.fileName = file.name;
      const formData = new FormData();
      formData.append("file", file);

      const upload$ = this.http.post("http://localhost:8081/upload", formData, {
        reportProgress: true,
        observe: 'events'
      })
        .pipe(
          finalize(() => this.reset())
        );

      this.uploadSub = upload$.subscribe((event: any) => {
        if (event.type == HttpEventType.UploadProgress) {
          this.uploadProgress = Math.round(100 * (event.loaded / event.total));
        } else if (event.type === HttpEventType.Response) {
          this.propagateChange({ id: 1, file: event.body.file});
          this.icon = event.body.file;
          this.onUploadComplete.emit(event.body.file);
        }
      })
    }
  }

  cancelUpload() {
    this.uploadSub.unsubscribe();
    this.reset();
  }

  reset() {
    this.uploadProgress = null;
    this.uploadSub = null;
  }
}
