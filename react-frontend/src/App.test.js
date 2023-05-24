import { render } from '@testing-library/react';
import AllDeals from './AllDeals';

test('renders all deals without crashing', () => {
  render(<AllDeals />);
});