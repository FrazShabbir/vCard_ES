export function formatPkr(paisa: number): string {
  const rupees = Math.round(paisa / 100)
  return 'Rs ' + rupees.toLocaleString('en-PK')
}
