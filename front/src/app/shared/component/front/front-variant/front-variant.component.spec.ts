import { ComponentFixture, TestBed } from '@angular/core/testing';

import { FrontVariantComponent } from './front-variant.component';

describe('FrontVariantComponent', () => {
  let component: FrontVariantComponent;
  let fixture: ComponentFixture<FrontVariantComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ FrontVariantComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(FrontVariantComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
