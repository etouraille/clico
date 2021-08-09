import { ComponentFixture, TestBed } from '@angular/core/testing';

import { RemoveVariantComponent } from './remove-variant.component';

describe('RemoveVariantComponent', () => {
  let component: RemoveVariantComponent;
  let fixture: ComponentFixture<RemoveVariantComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ RemoveVariantComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(RemoveVariantComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
