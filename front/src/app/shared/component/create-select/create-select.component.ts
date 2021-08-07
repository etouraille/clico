import {AfterViewInit, ChangeDetectorRef, Component, ElementRef, forwardRef, OnInit, ViewChild} from '@angular/core';
import {BehaviorSubject, fromEvent} from "rxjs";
import {debounceTime, distinctUntilChanged, switchMap, tap} from "rxjs/operators";
import {HttpClient} from "@angular/common/http";
import * as $ from 'jquery';
import {ControlValueAccessor, FormArray, FormControl, NG_VALUE_ACCESSOR} from "@angular/forms";

@Component({
  selector: 'app-create-select',
  templateUrl: './create-select.component.html',
  styleUrls: ['./create-select.component.css'],
  providers: [
    {
      provide: NG_VALUE_ACCESSOR,
      useExisting: forwardRef(() => CreateSelectComponent),
      multi: true
    }
  ]
})
export class CreateSelectComponent implements OnInit, AfterViewInit, ControlValueAccessor {

  @ViewChild('editable') editable: ElementRef;

  elementWaitingToAdd = [];
  suggestAddValue : string;
  isSuggested = false;
  elementAdded: any = [];
  subject: BehaviorSubject<string> = new BehaviorSubject<string>('');

  propagateChange = (_: any) => {};

  writeValue(value: any) {
    console.log( value);
    if (value) {
      this.elementAdded = value;
    }
  }
  registerOnChange(fn: any) {
    console.log('register');
    this.propagateChange = fn;
  }

  registerOnTouched(fn: any) {
  }

  constructor(private ref: ChangeDetectorRef, private http: HttpClient) { }


  ngOnInit(): void {
    this.subject.asObservable().subscribe((value:any) => {
      if (value) {
        this.isSuggested = true;
        this.suggestAddValue = value;
      } else {
        this.isSuggested = false;
        this.suggestAddValue = '';
      }
    })
  }

  ngAfterViewInit(): void {
    fromEvent(this.editable.nativeElement,'keyup')
      .pipe(
        debounceTime(150),
        distinctUntilChanged(),
        tap((event: any) => {
          if(event.code == 'Enter') {
            this.addSuggested();
          } else {
            this.subject.next(this.formatText(event.target.innerHTML));
          }
        }),
        switchMap((event : any) => this.http.post('/api/variant/query', { query: this.formatText(event.target.innerHTML)})

        ),
        tap((data: any) => {
          if(data.exists) {
            this.elementWaitingToAdd = data.variants.filter(
              (elem: any) => -1 === this.elementAdded.findIndex(added => added.id === elem.id));
          } else {
            this.elementWaitingToAdd = [];
          }
        })
      )
      .subscribe();

  }
  addSuggested() : void {
    this.elementAdded.push({ label: this.suggestAddValue});
    this.propagateChange(this.elementAdded);
    this.editable.nativeElement.innerHTML = '';
    this.subject.next('');
  }

  formatText(value: string) {
    return value
      .replace('&nbsp;', ' ')
      .replace('<div><br></div>','');
  }


  remains(): string {

    return this.elementAdded.length == 0 ? '100%' : '';
  }


  remove(index: number) {
    this.elementAdded.splice(index,1);
    this.propagateChange(this.elementAdded);
  }

  add(i): void {
    this.isSuggested = false;
    this.editable.nativeElement.innerHTML = '';
    this.subject.next('');
    this.elementAdded.push(this.elementWaitingToAdd[i]);
    this.propagateChange(this.elementAdded);
    this.elementWaitingToAdd = [];
  }
}
