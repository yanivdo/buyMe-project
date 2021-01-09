import { module, test } from 'qunit';
import { setupTest } from 'ember-qunit';

module('Unit | Route | entrance', function(hooks) {
  setupTest(hooks);

  test('it exists', function(assert) {
    let route = this.owner.lookup('route:entrance');
    assert.ok(route);
  });
});
