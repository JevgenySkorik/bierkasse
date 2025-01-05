# Bierkasse

## Ideas/Thoughts

* Users with different permissions
  * Regular users - able to access and add entries to the journal
  * Admin - able to access admin dashboard
  * Should regular users be able to access the Debt page and mark debts as paid? Probably not.
  * Basic auth for regular users, login for admins

* Debt page
  * Shows list of all people with debt
  * On-click shows all orders with debt for this person
  * Pay off separate orders or all of debt

## Todo

### Big tasks
- [ ] **Debt page**
- [ ] **Regular users/admins**

### Small tasks
- [ ] Don't use tailwind cdn
- [ ] Download title font and use locally

### Unsure about these
- [ ] Journal editing mode automatic total calculation? Maybe if displayed as placeholder text
- [ ] Name autocomplete based on existing entries
- [ ] Should you be able to delete journal entries?
- [ ] Basic api to work with the journal table

### Done
- [x] Allow renaming products
- [x] Allow same name products with different prices
- [x] Success messages
- [x] Combine save buttons into one at the top
- [x] **Export as csv**