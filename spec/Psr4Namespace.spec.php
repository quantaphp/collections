<?php

use Quanta\Collections\Directory;
use Quanta\Collections\ToPsr4Fqcn;
use Quanta\Collections\Psr4Namespace;
use Quanta\Collections\MappedCollection;
use Quanta\Collections\ToRelativePathname;
use Quanta\Collections\FilteredCollection;
use Quanta\Collections\IsClassDefinitionFile;

describe('Psr4Namespace', function () {

    context('when there is no filter', function () {

        beforeEach(function () {

            $this->collection = new Psr4Namespace('Test\\NS', __DIR__ . '/some/directory');

        });

        it('should implement IteratorAggregate', function () {

            expect($this->collection)->toBeAnInstanceOf(IteratorAggregate::class);

        });

        describe('->getIterator()', function () {

            it('should return a class collection with no filter', function () {

                $test = $this->collection->getIterator();

                expect($test)->toEqual(new FilteredCollection(
                    new MappedCollection(
                        new Directory(__DIR__ . '/some/directory', new IsClassDefinitionFile),
                        new ToRelativePathname(__DIR__ . '/some/directory'),
                        new ToPsr4Fqcn('Test\\NS')
                    )
                ));

            });

        });

    });

    context('when there is filters', function () {

        beforeEach(function () {

            $this->collection = new Psr4Namespace('Test\\NS', __DIR__ . '/some/directory', ...[
                $this->filter1 = function () {},
                $this->filter2 = function () {},
                $this->filter3 = function () {},
            ]);

        });

        it('should implement IteratorAggregate', function () {

            expect($this->collection)->toBeAnInstanceOf(IteratorAggregate::class);

        });

        describe('->getIterator()', function () {

            it('should return a class collection with the filters', function () {

                $test = $this->collection->getIterator();

                expect($test)->toEqual(new FilteredCollection(
                    new MappedCollection(
                        new Directory(__DIR__ . '/some/directory', new IsClassDefinitionFile),
                        new ToRelativePathname(__DIR__ . '/some/directory'),
                        new ToPsr4Fqcn('Test\\NS')
                    ),
                    $this->filter1,
                    $this->filter2,
                    $this->filter3
                ));

            });

        });

    });

});
