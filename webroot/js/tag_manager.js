class TagManager {
    /**
     * Constructor
     *
     * Options
     * - tags: A tree-shaped object containing nested tag groups
     * - selectedTags: An array of objects with 'id' and 'name' properties
     * - showTree: (default: true)
     * - showList: (default: false)
     * - useAutocomplete: (default: false)
     * - container: (default: '#available-tags')
     *
     * @param options
     */
    constructor(options) {
        this.tags = options.hasOwnProperty('tags') ? options.tags : [];
        this.container = options.hasOwnProperty('container')
            ? document.querySelector(options.container)
            : document.getElementById('available-tags');

        const showTree = options.hasOwnProperty('showTree') ? options.showTree : true;
        if (showTree) {
            this.createTagTree();
        }

        const showList = options.hasOwnProperty('showList') ? options.showList : false;
        this.tagList = [];
        this.tagsIds = {};
        if (showList) {
            this.createTagList();
        }

        if (showTree && showList) {
            this.setupTabs();
        }

        const selectedTags = options.hasOwnProperty('selectedTags') ? options.selectedTags : [];
        if (selectedTags.length > 0) {
            this.preselectTags(selectedTags);
        }

        const useAutocomplete = options.hasOwnProperty('useAutocomplete') ? options.useAutocomplete : false;
        if (useAutocomplete) {
            this.setupAutosuggest();
        }

        const exampleTag = document.getElementById('example-selectable-tag');
        exampleTag.addEventListener('click', function (event) {
            event.preventDefault();
        });
    }

    // Adapted from the jQuery UI "transfer" effect
    transfer(element, target, done) {
        const targetFixed = target.style.position === 'fixed';
        const body = document.querySelector('body');
        const fixTop = targetFixed ? body.scrollTop : 0;
        const fixLeft = targetFixed ? body.scrollLeft : 0;
        const endPosition = target.getBoundingClientRect();
        const animation = {
            top: endPosition.top - fixTop,
            left: endPosition.left - fixLeft,
            height: window.getComputedStyle(target).height,
            width: window.getComputedStyle(target).width,
        };
        const startPosition = element.getBoundingClientRect();
        const transfer = document.createElement('div');
        transfer.classList.add('ui-effects-transfer');
        transfer.style.top = (startPosition.top - fixTop) + 'px';
        transfer.style.left = (startPosition.left - fixLeft) + 'px';
        transfer.style.height = window.getComputedStyle(element).height;
        transfer.style.width = window.getComputedStyle(element).width;
        transfer.style.position = targetFixed ? 'fixed' : 'absolute';
        const duration = 1000;
        transfer.style.transitionDuration = duration + 'ms';
        body.append(transfer);
        transfer.style.top = animation.top + 'px';
        transfer.style.left = animation.left + 'px';
        transfer.style.height = animation.height + 'px';
        transfer.style.width = animation.width + 'px';
        setTimeout(function () {
            transfer.remove();
            if (typeof done !== 'undefined') {
                done();
            }
        }, duration);
    }

    getTab(tabId, targetId, label, selected) {
        return '<li class="nav-item" role="presentation">' +
            `<a class="nav-link active" id="${tabId}" data-toggle="tab" href="#${targetId}" role="tab" ` +
            `aria-controls="home" aria-selected="${selected ? 'true' : 'false'}">${label}</a>` +
            '</li>';
    }

    setupTabs() {
        const tabs = document.createElement('ul');
        tabs.classList.add('nav', 'nav-tabs');
        tabs.innerHTML = this.getTab('tag-manager-tree-tab', 'tag-manager-tree', 'Tree', true);
        tabs.innerHTML += this.getTab('tag-manager-list-tab', 'tag-manager-list', 'List', false);
        this.container.prepend(tabs);
    }

    showError(message) {
        const alert = document.createElement('p');
        alert.classList.add('alert', 'alert-danger');
        alert.innerHtml = message;
        this.container.prepend(alert);
    }

    createTagTree() {
        const treeContainer = document.createElement('div');
        treeContainer.id = 'available-tags-tree';
        this.container.append(treeContainer);
        this.createTagTreeBranch(this.tags, treeContainer);
    }

    /**
     * Appends to container a branch of the tag tree
     *
     * @param {Object[]} data - An array of tag objects
     * @param {Object} container - $('#container_id')
     */
    createTagTreeBranch(data, container) {
        const list = document.createElement('ul');
        const self = this;
        for (let i = 0; i < data.length; i++) {
            const tagId = data[i].id;
            const tagName = data[i].name;
            const children = data[i].children;
            const hasChildren = (children.length > 0);
            const isSelectable = data[i].selectable;
            const listItem = document.createElement('li');
            listItem.dataset.tagId = tagId;
            const row = document.createElement('div');
            row.classList.add('single_row');
            listItem.append(row);
            list.append(listItem);
            let tagLink;

            if (isSelectable) {
                tagLink = document.createElement('a');
                tagLink.href = '#';
                tagLink.title = 'Click to select';
                tagLink.dataset.tagId = tagId;
                tagLink.classList.add('available_tag');
                tagLink.innerText = tagName;
                (function (tagId) {
                    tagLink.addEventListener('click', function (event) {
                        event.preventDefault();
                        const link = event.target;
                        const tagName = link.innerHtml;
                        const listItem = link.parentElement;
                        self.selectTag(tagId, tagName, listItem);
                    });
                })(tagId);
            }

            // Bullet point
            if (hasChildren) {
                const collapsedIcon = document.createElement('a');
                collapsedIcon.href = '#';
                collapsedIcon.title = 'Click to expand/collapse';
                const icon = document.createElement('i');
                icon.className = 'fas fa-caret-right expand_collapse';
                collapsedIcon.append(icon);
                (function(children) {
                    collapsedIcon.addEventListener('click', function(event) {
                        event.preventDefault();
                        const icon = event.target;
                        const iconContainer = icon.parentElement;
                        const childrenContainer = self.next(iconContainer, '.children');

                        // Populate list if it is empty
                        const isEmpty = childrenContainer.querySelector('ul').length === 0;
                        if (isEmpty) {
                            self.createTagTreeBranch(children, childrenContainer);
                        }

                        // Open/close
                        const duration = 200;
                        slideToggle(childrenContainer);
                        setTimeout(function() {
                            const icon = icon.querySelector('i.expand_collapse');
                            const isVisible = childrenContainer.style.display !== 'none';
                            if (isVisible) {
                                icon.classList.remove('fa-caret-right');
                                icon.classList.add('fa-caret-down');
                            } else {
                                icon.classList.remove('fa-caret-down');
                                icon.classList.add('fa-caret-right');
                            }
                        }, duration);
                    });
                })(children);

                row.append(collapsedIcon);
            } else {
                const leaf = document.createElement('i');
                leaf.className = 'far fa-minus leaf';
                row.append(leaf);
            }

            if (isSelectable) {
                row.append(tagLink);
            } else {
                row.append(tagName);
            }

            // Tag and submenu
            if (hasChildren) {
                const childrenContainer = document.createElement('div');
                childrenContainer.style.display = 'none';
                childrenContainer.classList.add('children');
                row.after(childrenContainer);
            }

            // If tag has been selected
            if (isSelectable && this.tagIsSelected(tagId)) {
                tagName.classList.add('selected');
                if (!hasChildren) {
                    listItem.style.display = 'none';
                }
            }
        }
        container.append(list);
    }

    // Source: https://gomakethings.com/finding-the-next-and-previous-sibling-elements-that-match-a-selector-with-vanilla-js/
    next(elem, selector) {
        // Get the next sibling element
        let sibling = elem.nextElementSibling;

        // If there's no selector, return the first sibling
        if (!selector) {
            return sibling;
        }

        // If the sibling matches our selector, use it
        // If not, jump to the next sibling and continue the loop
        while (sibling) {
            if (sibling.matches(selector)) {
                return sibling;
            }
            sibling = sibling.nextElementSibling
        }
    }

    createTagList() {
        this.processTagList(this.tags);
        const list = document.createElement('ul');
        const self = this;
        for (let i = 0; i < this.tagList.length; i++) {
            const tagName = this.tagList[i];
            const tagId = this.tagsIds[tagName];
            const listItem = document.createElement('li');
            listItem.dataset.tagId = tagId;

            const tagLink = document.createElement('a');
            tagLink.href = '#';
            tagLink.classList.add('available_tag');
            tagLink.title = 'Click to select';
            tagLink.dataset.tagId = tagId;
            tagLink.append(tagName);
            (function(tagId) {
                tagLink.addEventListener('click', function (event) {
                    event.preventDefault();
                    const link = event.target;
                    const tagName = link.innerHtml;
                    const listItem = link.parentElement;
                    self.selectTag(tagId, tagName, listItem);
                });
            })(tagId);
            listItem.append(tagLink);
            list.append(listItem);
        }

        const listContainer = document.createElement('div');
        listContainer.id = 'available-tags-list';
        listContainer.append(list);
        this.container.append(listContainer);
    }

    processTagList(data) {
        for (let i = 0; i < data.length; i++) {
            const tagId = data[i].id;
            const tagName = data[i].name;
            const children = data[i].children;
            const hasChildren = children.length > 0;
            const isSelectable = data[i].selectable;
            if (isSelectable) {
                this.tagList.push(tagName);
                this.tagsIds[tagName] = tagId;
            }
            if (hasChildren) {
                this.processTagList(children);
            }
        }
    }

    tagIsSelected(tagId) {
        const tag = document.querySelector(`#selectedTags a[data-tag-id="${tagId}"]`);

        return tag !== null;
    }

    preselectTags(selectedTags) {
        if (selectedTags.length === 0) {
            return;
        }
        document.getElementById('selected-tags-container').style.display = 'block';
        for (let i = 0; i < selectedTags.length; i++) {
            this.selectTag(selectedTags[i].id, selectedTags[i].name);
        }
    }

    unselectTag(tagId, unselectLink) {
        const availableTagLinks = this.container.querySelectorAll(`a[data-tag-id="${tagId}"]`);

        // If available tag has not yet been loaded, then simply remove the selected tag
        if (availableTagLinks.length === 0) {
            this.removeUnselectLink(unselectLink);
            return;
        }

        const self = this;
        availableTagLinks.forEach(function (availableTagLink) {
            availableTagLink.classList.remove('selected');
            const li = availableTagLink.parentElement;
            const openTab = availableTagLink.closest('#available-tags-tree, #available-tags-list');
            const tabIsVisible = openTab.style.display !== 'none';

            // If this link is in an unopened tab, don't animate anything
            if (!tabIsVisible) {
                li.style.display = 'list-item';

                /* Only remove the unselect link if this is the only iteration of this loop.
                 * Otherwise, the link in the opened tab needs the unselect link present for the transfer effect. */
                if (availableTagLinks.length === 1) {
                    self.removeUnselectLink(unselectLink);
                }
                return;
            }

            const transferEffect = function () {
                // Don't show the transfer effect if there's no visible link to transfer to
                if (!self.availableTagIsVisible(availableTagLink, openTab)) {
                    self.removeUnselectLink(unselectLink);
                    return;
                }
                self.transfer(availableTagLink, unselectLink, function () {
                    self.removeUnselectLink(unselectLink);
                });
            };

            // If the link container doesn't need to be revealed
            if (tabIsVisible && li.style.display !== 'none') {
                transferEffect();

            // If the link container needs to be revealed (and would be visible during the reveal)
            } else if (li.parentElement.style.display !== 'none') {
                li.slideDown(200, function () {
                    transferEffect();
                });

            } else {
                li.style.display = 'list-item';
                self.removeUnselectLink(unselectLink);
            }
        });
    }

    availableTagIsVisible(link, scrollableArea) {
        if (this.isVisible(link)) {
            return false;
        }

        return (link.offsetTop + link.offsetHeight > 0 && link.offsetTop < scrollableArea.offsetHeight);
    }

    isVisible(element) {
        return !!(element.offsetWidth || element.offsetHeight || element.getClientRects().length);
    }

    removeUnselectLink(unselectLink) {
        unselectLink.remove();
        const selectedTags = document.getElementById('selected-tags');
        if (selectedTags.childNodes.length === 0) {
            const selectedTagsContainer = document.getElementById('selected-tags-container');
            slideUp(selectedTagsContainer);
        }
    }

    selectTag(tagId, tagName) {
        const selectedContainer = document.getElementById('selected-tags-container');
        if (!this.isVisible(selectedContainer)) {
            slideDown(selectedContainer);
        }

        // Do not add tag if it is already selected
        if (this.tagIsSelected(tagId)) {
            return;
        }

        // Add tag
        const listItem = document.createElement('a');
        listItem.href = '#';
        listItem.title = 'Click to remove';
        listItem.dataset.tagId = tagId;
        listItem.append(tagName);
        listItem.append(`<input type="hidden" name="tags[]" value="${tagId}" />`);
        const self = this;
        listItem.addEventListener('click', function (event) {
            event.preventDefault();
            const unselectLink = event.target;
            const tagId = unselectLink.dataset.tagId;
            self.unselectTag(tagId, unselectLink);
        });
        const selectedTags = document.getElementById('selected-tags');
        selectedTags.append(listItem);

        // If available tag has not yet been loaded, then there's no need to mess with its link
        const tagLi = this.container.querySelector(`li[data-tag-id="${tagId}"]`);
        if (tagLi == null) {
            return;
        }

        // Hide/update links to add tag
        const links = this.container.querySelectorAll(`a[data-tag-id="${tagId}"]`);
        links.forEach(function (link) {
            const callback = function () {
                link.classList.add('selected');
                const parentLi = link.closest('li');
                const children = parentLi.querySelectorAll('.children');
                if (children.length === 0) {
                    if (self.isVisible(parentLi)) {
                        slideUp(parentLi);
                    } else {
                        parentLi.style.display = 'none';
                    }
                }
            };
            if (self.isVisible(link)) {
                const target = document.querySelectorAll(`#selectedTags a[data-tag-id="${tagId}"]`);
                self.transfer(link, target, callback);
            } else {
                callback();
            }
        });
    }

    // Reference: https://tarekraafat.github.io/autoComplete.js/
    setupAutosuggest() {
        const inputId = 'custom-tag-input';
        const input = document.getElementById(inputId);
        const self = this;

        new autoComplete({
            data: {
                src: async () => {
                    const term = this.extractLast(input.value);
                    const source = await fetch(`/tags/autocomplete.json?term=${term}`);

                    return await source.json();
                },
                cache: false,
            },
            selector: '#' + inputId,
            resultsList: {
                // Hide search results upon clicking outside of their container
                container: source => {
                    source.setAttribute('id', 'autoComplete_list');
                    input.addEventListener('autoComplete', function () {
                        function hideSearchResults() {
                            const searchResults = document.getElementById('autoComplete_list');
                            while (searchResults.firstChild) {
                                searchResults.removeChild(searchResults.firstChild);
                            }
                            document.removeEventListener('click', hideSearchResults);
                        }
                        document.addEventListener('click', hideSearchResults);
                    })
                },
                destination: document.querySelector(inputId),
                render: true,
            },
            onSelection: function (feedback) {
                console.log(feedback);
                const tagName = feedback.selection.label;
                const tagId = feedback.selection.value;
                self.selectTag(tagId, tagName);

                const terms = self.split(input.value);

                // Remove the term being typed from the input field
                terms.pop();

                if (terms.length > 0) {
                    // Add placeholder to get an extra comma-and-space at the end
                    terms.push('');
                }

                input.value = terms.join(', ');
            }
        });
    }

    split(val) {
        return val.split(/,\s*/);
    }

    extractLast(term) {
        return this.split(term).pop();
    }
}
